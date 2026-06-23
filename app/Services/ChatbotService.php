<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ChatbotApiKey;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotLog;
use App\Models\PpdbSetting;
use App\Models\Prestasi;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Batas aman karakter context yang dikirim ke AI agar tidak membebani
     * context window model (terutama provider dengan window kecil seperti
     * Groq/DeepSeek). ~12000 karakter ≈ 3000-4000 token.
     */
    protected const MAX_CONTEXT_CHARS = 12000;

    /**
     * Send a query to the Gemini AI API, handling API key rotation and context gathering.
     *
     * @return array{text: string, api_key_used_id: int|null, tokens_used: int}
     *
     * @throws \Exception
     */
    public function askAI(string $query, array $chatHistory = []): array
    {
        $context = $this->gatherContext($query);

        // Fetch all active API keys, sorted by error count
        $apiKeys = ChatbotApiKey::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('limit_reached_at')
                    ->orWhere('limit_reached_at', '<', now()->subMinutes(30));
            })
            ->orderBy('error_count', 'asc')
            ->get();

        if ($apiKeys->isEmpty()) {
            ChatbotLog::create([
                'level' => 'error',
                'message' => 'Tidak ada API Key AI yang aktif atau tersedia (semua mungkin dalam masa cooldown 30 menit atau dinonaktifkan).',
            ]);
            throw new \Exception('Maaf, tidak ada API Key AI yang aktif atau tersedia saat ini.');
        }

        $lastException = null;
        $errorLogs = [];

        foreach ($apiKeys as $keyRecord) {
            try {
                $callResult = $this->callApi($keyRecord, $context, $query, $chatHistory);

                // Success! Reset error count
                $keyRecord->update([
                    'error_count' => 0,
                    'limit_reached_at' => null,
                ]);

                // Log warning/info if fallback occurred before succeeding
                if (! empty($errorLogs)) {
                    ChatbotLog::create([
                        'api_key_id' => $keyRecord->id,
                        'level' => 'warning',
                        'message' => "Chatbot berhasil memberikan respons AI menggunakan API Key ID {$keyRecord->id} (Provider: {$keyRecord->provider}, Model: {$keyRecord->model_name}) setelah mencoba beberapa Kunci API lainnya yang mengalami gangguan.",
                        'payload' => [
                            'fallback_sequence' => $errorLogs,
                            'query' => $query,
                        ],
                    ]);
                }

                return [
                    'text' => $callResult['text'],
                    'api_key_used_id' => $keyRecord->id,
                    'tokens_used' => $callResult['tokens_used'] ?? $this->estimateTokens($callResult['text'], $context, $query),
                ];
            } catch (\Exception $e) {
                $lastException = $e;
                $errMessage = $e->getMessage();
                $errType = 'Unknown Error';
                $isRateLimit = false;

                // Categorize error
                if (str_contains(strtolower($errMessage), 'status 429') || str_contains(strtolower($errMessage), 'rate limit') || str_contains(strtolower($errMessage), 'quota exceeded') || str_contains(strtolower($errMessage), 'too many requests')) {
                    $errType = 'Rate Limit / Quota Exceeded';
                    $isRateLimit = true; // Only this type triggers cooldown
                } elseif (str_contains(strtolower($errMessage), 'status 401') || str_contains(strtolower($errMessage), 'status 403') || str_contains(strtolower($errMessage), 'invalid api key') || str_contains(strtolower($errMessage), 'api key not found') || str_contains(strtolower($errMessage), 'unauthorized') || str_contains(strtolower($errMessage), 'forbidden')) {
                    $errType = 'API Key Salah / Invalid';
                } elseif (str_contains(strtolower($errMessage), 'timeout') || str_contains(strtolower($errMessage), 'resolving host') || str_contains(strtolower($errMessage), 'curl error') || str_contains(strtolower($errMessage), 'connection')) {
                    $errType = 'Timeout / Masalah Koneksi Jaringan';
                } elseif (str_contains(strtolower($errMessage), 'status 404') || str_contains(strtolower($errMessage), 'model not found') || str_contains(strtolower($errMessage), 'unknown model') || str_contains(strtolower($errMessage), 'model_not_found') || str_contains(strtolower($errMessage), 'does not exist')) {
                    $errType = 'Model Tidak Ditemukan';
                } elseif (str_contains(strtolower($errMessage), 'provider tidak didukung')) {
                    $errType = 'Provider Tidak Didukung';
                }

                $errorLogs[] = [
                    'api_key_id' => $keyRecord->id,
                    'provider' => $keyRecord->provider,
                    'model_name' => $keyRecord->model_name,
                    'error_type' => $errType,
                    'error_message' => $errMessage,
                    'occurred_at' => now()->toIso8601String(),
                ];

                Log::warning("Chatbot API Key ID {$keyRecord->id} ({$keyRecord->provider}/{$keyRecord->model_name}) failed [{$errType}]: ".$e->getMessage());

                // Only set cooldown for rate limit errors; other errors (wrong model, bad key)
                // should NOT block the key from being tried again on next request.
                $keyRecord->update([
                    'error_count' => $keyRecord->error_count + 1,
                    'limit_reached_at' => $isRateLimit ? now() : null,
                ]);
            }
        }

        $exhaustedMessage = 'Maaf, semua API Key AI sedang mengalami limitasi kuota atau gangguan teknis: '.($lastException ? $lastException->getMessage() : 'Unknown Error');
        ChatbotLog::create([
            'level' => 'error',
            'message' => 'Semua API Key AI yang tersedia gagal dicoba dan tidak ada respons valid.',
            'payload' => [
                'fallback_sequence' => $errorLogs,
                'last_exception' => $lastException ? $lastException->getMessage() : 'Unknown Error',
            ],
        ]);

        throw new \Exception($exhaustedMessage);
    }

    /**
     * Call the appropriate API based on key provider.
     *
     * @return array{text: string, tokens_used: int|null}
     *
     * @throws \Exception
     */
    protected function callApi(ChatbotApiKey $apiKeyRecord, string $context, string $query, array $chatHistory): array
    {
        $provider = strtolower($apiKeyRecord->provider);
        $model = $apiKeyRecord->model_name;
        $apiKey = $apiKeyRecord->api_key;

        $systemInstruction = $this->buildSystemInstruction($context);

        if ($provider === 'gemini') {
            return $this->callGeminiApi($model, $apiKey, $systemInstruction, $query, $chatHistory);
        } elseif ($provider === 'groq') {
            return $this->callOpenAiCompatibleApi('https://api.groq.com/openai/v1/chat/completions', $model, $apiKey, $systemInstruction, $query, $chatHistory);
        } elseif ($provider === 'deepseek') {
            return $this->callOpenAiCompatibleApi('https://api.deepseek.com/chat/completions', $model, $apiKey, $systemInstruction, $query, $chatHistory);
        } elseif ($provider === 'openrouter') {
            return $this->callOpenAiCompatibleApi('https://openrouter.ai/api/v1/chat/completions', $model, $apiKey, $systemInstruction, $query, $chatHistory, [
                'HTTP-Referer' => config('app.url') ?? 'https://mamlimpung.sch.id',
                'X-Title' => 'MA Muhammadiyah Limpung Chatbot',
            ]);
        } else {
            throw new \Exception("Provider AI '{$provider}' tidak didukung.");
        }
    }

    /**
     * Build the system instruction sent to the AI model.
     */
    protected function buildSystemInstruction(string $context): string
    {
        return 'Kamu adalah Asisten AI MA Muhammadiyah Limpung. Ngobrol santai kayak kakak kelas atau teman, bukan kayak robot formal — pakai bahasa sehari-hari (boleh "kamu", "aku/min", "yuk", "nih", "kok") biar siswa ngerasa deket dan nggak sungkan tanya. '
            .'Pakai emoji secukupnya sesuai konteks biar hangat, tapi jangan berlebihan, cukup 1-3 emoji per jawaban '
            .'(contoh: 😊🙌 sambutan, 📚 artikel/edukasi, 🏆 prestasi, 📋 PPDB/pendaftaran, 📞 kontak, 🏫 info sekolah). '
            .'Jawab berdasarkan DATA AKTUAL dari konteks di bawah — SANGAT PENTING: jika konteks menyebut angka jumlah (misalnya "Total: 25 artikel", "Total: 10 prestasi"), '
            .'WAJIB sebutkan angka tersebut secara eksplisit dalam jawaban. Jangan mengarang angka. '
            .'Jawab SINGKAT, PADAT, dan ngalir kayak ngomong biasa (maksimal 2 paragraf pendek, total ≤ 120 kata). Jawaban harus selesai penuh, jangan terpotong. '
            .'Variasikan gaya bukaan kalimat, jangan selalu mulai dengan kata yang sama biar nggak terasa template. '
            .'Jika pengguna menanyakan halaman tertentu, artikel terbaru, atau pendaftaran PPDB, tambahkan tombol navigasi di akhir: [BUTTON: Label Tombol|URL] '
            .'Jika informasi nggak ada di konteks, jawab jujur aja dan arahkan ke WhatsApp Admin. '
            ."JANGAN pernah ngarang informasi yang nggak ada di konteks.\n\n"
            ."DATA AKTUAL SEKOLAH:\n".$context;
    }

    /**
     * Call the Google Gemini native API.
     *
     * @return array{text: string, tokens_used: int|null}
     *
     * @throws \Exception
     */
    protected function callGeminiApi(string $model, string $apiKey, string $systemInstruction, string $query, array $chatHistory): array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        // Build contents payload including history if any
        $contents = [];

        // Add history
        foreach ($chatHistory as $msg) {
            $role = $msg['sender'] === 'user' ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $msg['message']],
                ],
            ];
        }

        // Add current user prompt
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $query],
            ],
        ];

        $payload = [
            'contents' => $contents,
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                // Diberi headroom lebih besar dari batas ~120 kata (≈180-220 token)
                // karena beberapa model Gemini (terutama varian "thinking") memakai
                // sebagian token output untuk reasoning internal sebelum menulis
                // jawaban akhir — jika maxOutputTokens terlalu kecil, jawaban akan
                // terpotong di tengah kalimat.
                'maxOutputTokens' => 800,
            ],
            // Matikan/minimalkan thinking budget agar token tidak terbuang untuk
            // reasoning tersembunyi (didukung oleh model Gemini 2.5 series).
            'thinkingConfig' => [
                'thinkingBudget' => 0,
            ],
        ];

        $request = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(20);

        if (app()->environment('local')) {
            $request->withoutVerifying();
        }

        $response = $request->post($url, $payload);

        if ($response->failed()) {
            $errBody = $response->json();
            $errMsg = $errBody['error']['message'] ?? $response->body() ?? 'API Request Failed';
            throw new \Exception("Gemini API Error (Status {$response->status()}): {$errMsg}");
        }

        $result = $response->json();
        $candidate = $result['candidates'][0] ?? null;
        $text = $candidate['content']['parts'][0]['text'] ?? null;
        $finishReason = $candidate['finishReason'] ?? null;

        if (! $text) {
            // finishReason MAX_TOKENS tanpa teks biasanya berarti seluruh budget
            // terpakai untuk thinking — anggap sebagai error agar key/provider
            // lain dicoba, bukan mengembalikan jawaban kosong.
            throw new \Exception(
                'Gemini API returned an empty response candidate'
                .($finishReason ? " (finishReason: {$finishReason})" : '').'.'
            );
        }

        if ($finishReason === 'MAX_TOKENS') {
            // Jawaban kemungkinan terpotong di tengah kalimat. Lempar exception
            // supaya askAI() mencoba API key/provider berikutnya yang mungkin
            // punya budget output lebih besar, alih-alih mengirim teks terpotong.
            throw new \Exception('Gemini API response terpotong (finishReason: MAX_TOKENS).');
        }

        // Gemini menyediakan usageMetadata yang lebih akurat dibanding estimasi manual.
        $tokensUsed = $result['usageMetadata']['totalTokenCount'] ?? null;

        return [
            'text' => trim($text),
            'tokens_used' => $tokensUsed,
        ];
    }

    /**
     * Call OpenAI Chat Completions compatible API (for Groq, DeepSeek, OpenRouter).
     *
     * @return array{text: string, tokens_used: int|null}
     *
     * @throws \Exception
     */
    protected function callOpenAiCompatibleApi(string $url, string $model, string $apiKey, string $systemInstruction, string $query, array $chatHistory, array $extraHeaders = []): array
    {
        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => $systemInstruction,
        ];

        foreach ($chatHistory as $msg) {
            $role = $msg['sender'] === 'user' ? 'user' : 'assistant';
            $messages[] = [
                'role' => $role,
                'content' => $msg['message'],
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $query,
        ];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.4,
            // Headroom lebih besar dari batas ~120 kata agar jawaban tidak
            // terpotong di tengah kalimat, terutama untuk model reasoning
            // yang memakai sebagian token untuk "thinking" sebelum jawaban.
            'max_tokens' => 800,
        ];

        $headers = array_merge([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ], $extraHeaders);

        $request = Http::withHeaders($headers)->timeout(20);

        if (app()->environment('local')) {
            $request->withoutVerifying();
        }

        $response = $request->post($url, $payload);

        if ($response->failed()) {
            $errBody = $response->json();
            $errMsg = $errBody['error']['message'] ?? $response->body() ?? 'API Request Failed';
            throw new \Exception("API Error (Status {$response->status()}): {$errMsg}");
        }

        $result = $response->json();
        $choice = $result['choices'][0] ?? null;
        $text = $choice['message']['content'] ?? null;
        $finishReason = $choice['finish_reason'] ?? null;

        if (! $text) {
            throw new \Exception(
                'API returned an empty response candidate'
                .($finishReason ? " (finish_reason: {$finishReason})" : '').'.'
            );
        }

        if ($finishReason === 'length') {
            // Jawaban kemungkinan terpotong di tengah kalimat. Lempar exception
            // supaya askAI() mencoba API key/provider berikutnya.
            throw new \Exception('API response terpotong (finish_reason: length).');
        }

        // OpenAI-compatible providers (Groq, DeepSeek, OpenRouter) menyediakan usage.total_tokens.
        $tokensUsed = $result['usage']['total_tokens'] ?? null;

        return [
            'text' => trim($text),
            'tokens_used' => $tokensUsed,
        ];
    }

    /**
     * Estimasi token sebagai fallback ketika API tidak mengembalikan usage info.
     * Untuk teks Bahasa Indonesia, rasio karakter:token berkisar ~3.2-3.8.
     * Menggunakan strlen/3.5 jauh lebih akurat dibanding str_word_count.
     */
    protected function estimateTokens(string $responseText, string $context, string $query): int
    {
        $totalChars = strlen($responseText) + strlen($context) + strlen($query);

        return (int) ceil($totalChars / 3.5) + 50; // +50 buffer untuk system instruction overhead
    }

    /**
     * Gather relevant context from database according to selected topic and user query keywords.
     */
    public function gatherContext(string $query): string
    {
        $contextParts = [];
        $lq = strtolower($query);

        // 1. Load General School Info (cached — data ini jarang berubah)
        $siteInfo = Cache::remember('chatbot:site_info_context', now()->addHours(6), function () {
            $site = SiteSetting::first();
            if (! $site) {
                return null;
            }

            return 'Nama Sekolah: '.($site->school_name ?? 'MA Muhammadiyah Limpung')."\n"
                .'Motto: '.($site->school_motto ?? '-')."\n"
                .'Alamat: '.($site->address ?? '-')."\n"
                .'Email Resmi: '.($site->school_email_official ?? $site->email ?? '-')."\n"
                .'Nomor Telepon: '.($site->phone ?? '-')."\n"
                .'WhatsApp Admin: '.($site->whatsapp ?? '-')."\n"
                .'Kepala Sekolah: '.($site->headmaster_name ?? '-').' (NIP: '.($site->headmaster_nip ?? '-').")\n"
                .'Profil Singkat: '.($site->about_short ?? '-');
        });

        if ($siteInfo) {
            $contextParts[] = $siteInfo;
        }

        // 2. Load School Navigation URLs (cached — route() jarang berubah per deploy)
        // Hanya disertakan jika query terindikasi butuh navigasi, untuk hemat token
        // pada percakapan ringan (sapaan, basa-basi, dll).
        $needsNavigation = (bool) preg_match(
            '/\b(halaman|link|tautan|url|alamat\s*web|website|situs|kunjungi|buka|akses|daftar|pendaftaran|ppdb|artikel|berita|prestasi|galeri|profil|kontak|hubungi|kurikulum|ekstrakurikuler|jurusan|guru|pegawai|cek\s*status)\b/iu',
            $lq
        );

        if ($needsNavigation) {
            $urlText = Cache::remember('chatbot:nav_urls_context', now()->addHours(6), function () {
                $urls = [
                    'Beranda Sekolah' => route('frontend.home'),
                    'Halaman Informasi PPDB' => route('frontend.ppdb.index'),
                    'Formulir Pendaftaran PPDB Online' => route('frontend.ppdb.form'),
                    'Halaman Cek Status Pendaftaran PPDB' => route('frontend.ppdb.status'),
                    'Halaman Artikel & Berita Terbaru' => route('frontend.article.index'),
                    'Halaman Prestasi Sekolah' => route('frontend.prestasi'),
                    'Halaman Galeri Foto Kegiatan' => route('frontend.galeri'),
                    'Halaman Profil Sekolah (Visi Misi, Sejarah, dsb)' => route('frontend.profile'),
                    'Halaman Hubungi Kami (Kontak)' => route('frontend.contact'),
                    'Halaman Kurikulum' => route('frontend.kurikulum'),
                    'Halaman Ekstrakurikuler' => route('frontend.ekstrakurikuler'),
                    'Halaman Jurusan / Kompetensi Keahlian' => route('frontend.jurusan'),
                    'Halaman Direktori Guru & Pegawai' => route('frontend.pegawai.index'),
                ];

                $text = "Tautan/Link resmi sistem sekolah yang dapat diakses:\n";
                foreach ($urls as $name => $url) {
                    $text .= "- {$name}: {$url}\n";
                }

                return $text;
            });

            $contextParts[] = $urlText;
        }

        // 3. Load Knowledge Base Entries (filtered by keywords to save tokens)
        $cleanQuery = preg_replace('/[^\p{L}\p{N}\s]/u', '', $lq);

        // "berapa" sengaja TIDAK dimasukkan stop words agar count queries bisa terdeteksi
        $words = array_filter(explode(' ', $cleanQuery), function ($word) {
            $word = trim($word);
            $stopWords = ['yang', 'dan', 'untuk', 'atau', 'ini', 'itu', 'saya', 'anda', 'kami', 'kita',
                'mereka', 'dia', 'adalah', 'yaitu', 'yakni', 'pada', 'ke', 'dari', 'di', 'dengan',
                'secara', 'oleh', 'karena', 'sehingga', 'maka', 'namun', 'tetapi', 'saja', 'juga',
                'pun', 'ada', 'bisa', 'dapat', 'akan', 'telah', 'sudah', 'belum', 'baru', 'hanya',
                'sangat', 'amat', 'paling', 'lebih', 'kurang', 'seperti', 'bagai', 'tentang',
                'sebagai', 'apakah', 'bagaimana', 'kapan', 'siapa', 'mengapa', 'dimana',
                'halo', 'hai', 'tanya', 'dong', 'sih', 'kah', 'tolong', 'mohon'];

            return strlen($word) >= 3 && ! in_array($word, $stopWords);
        });

        $knowledgeQuery = ChatbotKnowledgeBase::where('is_active', true);

        if (! empty($words)) {
            $knowledgeQuery->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('title', 'like', "%{$word}%")
                        ->orWhere('content', 'like', "%{$word}%");
                }
            });
        }

        $knowledge = $knowledgeQuery->latest()->take(4)->get();

        if ($knowledge->isEmpty()) {
            $knowledge = ChatbotKnowledgeBase::where('is_active', true)->latest()->take(3)->get();
        }

        foreach ($knowledge as $item) {
            $contextParts[] = "Pengetahuan - {$item->title}:\n{$item->content}";
        }

        // 4. Keyword detection — perluas agar "berapa", "jumlah", "total" terdeteksi juga
        $isPPDBQuery = (bool) preg_match('/\b(ppdb|daftar|pendaftaran|registrasi|gelombang|syarat|biaya|siswa baru|ajaran|dokumen)\b/i', $lq);

        $isArticleQuery = (bool) preg_match('/\b(artikel|berita|publikasi|kegiatan|terbaru|kabar|info|pengumuman)\b/i', $lq);

        $isPrestasiQuery = (bool) preg_match('/\b(prestasi|juara|lomba|kompetisi|olimpiade|achievement|award)\b/i', $lq);

        // "ada berapa", "berapa jumlah", "total data" — tangkap query statistik umum,
        // tapi hindari kata generik "data"/"angka"/"banyak" sendirian agar tidak
        // memicu count query pada percakapan yang tidak relevan.
        $isCountQuery = (bool) preg_match('/\b(berapa|jumlah|total|statistik)\b/i', $lq);

        // Jika count query tanpa keyword spesifik, load semua kategori data
        if ($isCountQuery && ! $isPPDBQuery && ! $isArticleQuery && ! $isPrestasiQuery) {
            $isArticleQuery = true;
            $isPrestasiQuery = true;
        }

        // 5. PPDB context
        if ($isPPDBQuery) {
            $ppdbGeneral = PpdbSetting::getValue('general');
            $ppdbWaves = PpdbSetting::getValue('waves');
            $ppdbReqs = PpdbSetting::getValue('requirements');

            $ppdbText = "INFORMASI PENERIMAAN PESERTA DIDIK BARU (PPDB):\n";
            if ($ppdbGeneral) {
                $status = ($ppdbGeneral['is_open'] ?? false) ? 'DIBUKA' : 'DITUTUP';
                $ppdbText .= "- Status Pendaftaran PPDB: {$status}\n";
                $ppdbText .= '- Tahun Ajaran: '.($ppdbGeneral['tahun_ajaran'] ?? date('Y'))."\n";
            }
            if (! empty($ppdbWaves)) {
                $ppdbText .= "- Gelombang Pendaftaran:\n";
                foreach ($ppdbWaves as $wave) {
                    $ppdbText .= '  * '.($wave['name'] ?? 'Gelombang').': Tanggal '.($wave['start_date'] ?? '').' s/d '.($wave['end_date'] ?? '')."\n";
                }
            }
            if (! empty($ppdbReqs)) {
                $ppdbText .= "- Syarat Dokumen Pendaftaran:\n";
                foreach ($ppdbReqs as $req) {
                    if ($req['is_active'] ?? true) {
                        $reqText = ($req['required'] ?? false) ? 'Wajib' : 'Opsional';
                        $ppdbText .= '  * '.($req['label'] ?? '')." ({$reqText})\n";
                    }
                }
            }
            $contextParts[] = $ppdbText;
        }

        // 6. Article context — selalu sertakan TOTAL count agar AI bisa jawab "ada berapa"
        if ($isArticleQuery || $isCountQuery) {
            $totalArticles = Cache::remember('chatbot:total_articles', now()->addMinutes(15), function () {
                return Article::where('status', 'published')->count();
            });

            $articleTexts = "ARTIKEL DAN BERITA SEKOLAH:\n";
            $articleTexts .= "Total artikel diterbitkan: {$totalArticles} artikel\n";

            if ($isArticleQuery) {
                $articles = Article::where('status', 'published')
                    ->latest('published_at')
                    ->take(5)
                    ->get(['judul', 'ringkasan', 'published_at', 'slug']);

                if ($articles->isNotEmpty()) {
                    $articleTexts .= "5 Artikel Terbaru:\n";
                    foreach ($articles as $art) {
                        $date = $art->published_at ? $art->published_at->format('d M Y') : '';
                        $artUrl = route('frontend.article.show', $art->slug);
                        $articleTexts .= "- {$art->judul} ({$date}) — {$artUrl}\n";
                        if ($art->ringkasan) {
                            $articleTexts .= "  Ringkasan: {$art->ringkasan}\n";
                        }
                    }
                }
            }
            $contextParts[] = $articleTexts;
        }

        // 7. Prestasi context — selalu sertakan TOTAL count
        if ($isPrestasiQuery || $isCountQuery) {
            $totalPrestasi = Cache::remember('chatbot:total_prestasi', now()->addMinutes(15), function () {
                return Prestasi::count();
            });

            $achTexts = "PRESTASI SEKOLAH:\n";
            $achTexts .= "Total prestasi tercatat: {$totalPrestasi} prestasi\n";

            if ($isPrestasiQuery) {
                $achievements = Prestasi::latest()
                    ->take(5)
                    ->get(['judul', 'deskripsi', 'tingkat', 'jenis', 'penyelenggara', 'peraih', 'juara', 'tahun', 'tanggal_prestasi']);

                if ($achievements->isNotEmpty()) {
                    $achTexts .= "5 Prestasi Terbaru:\n";
                    foreach ($achievements as $ach) {
                        $date = $ach->tanggal_prestasi ? $ach->tanggal_prestasi->format('d M Y') : ($ach->tahun ?? '');
                        $tingkat = $ach->tingkat ? ucfirst($ach->tingkat) : '';
                        $achTexts .= "- {$ach->judul} ({$tingkat}, {$date})\n"
                            .'  Jenis: '.($ach->jenis ?? '-').' | Peraih: '.($ach->peraih ?? '-').' | Juara: '.($ach->juara ?? '-')."\n";
                    }
                }
            }
            $contextParts[] = $achTexts;
        }

        $finalContext = implode("\n\n=== SECTION ===\n\n", $contextParts);

        return $this->truncateContext($finalContext);
    }

    /**
     * Pastikan context tidak melebihi batas aman karakter agar tidak membebani
     * context window model dan tetap konsisten antar provider.
     */
    protected function truncateContext(string $context): string
    {
        if (strlen($context) <= self::MAX_CONTEXT_CHARS) {
            return $context;
        }

        return substr($context, 0, self::MAX_CONTEXT_CHARS)."\n\n[... konteks dipotong karena terlalu panjang ...]";
    }
}
