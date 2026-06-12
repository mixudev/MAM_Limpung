<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ChatbotApiKey;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotLog;
use App\Models\PpdbSetting;
use App\Models\Prestasi;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Send a query to the Gemini AI API, handling API key rotation and context gathering.
     *
     * @return array{text: string, api_key_used_id: int|null, tokens_used: int}
     *
     * @throws \Exception
     */
    public function askAI(string $query, string $topic = 'umum', array $chatHistory = []): array
    {
        $context = $this->gatherContext($topic, $query);

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

        $response = null;
        $apiKeyUsed = null;
        $lastException = null;
        $errorLogs = [];

        foreach ($apiKeys as $keyRecord) {
            try {
                $apiKeyUsed = $keyRecord;
                $responseText = $this->callApi($keyRecord, $context, $query, $chatHistory);

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
                    'text' => $responseText,
                    'api_key_used_id' => $keyRecord->id,
                    'tokens_used' => str_word_count($responseText) + str_word_count($context) + 100, // Estimate
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
     * @throws \Exception
     */
    protected function callApi(ChatbotApiKey $apiKeyRecord, string $context, string $query, array $chatHistory): string
    {
        $provider = strtolower($apiKeyRecord->provider);
        $model = $apiKeyRecord->model_name;
        $apiKey = $apiKeyRecord->api_key;

        // Structure system instructions
        $systemInstruction = 'Anda adalah Customer Service AI yang ramah, sopan, dan profesional dari MA Muhammadiyah Limpung. '
            .'Tugas Anda adalah membantu menjawab pertanyaan pengguna dengan berbasis PADA INFORMASI KONTEKS SEKOLAH yang disediakan di bawah ini. '
            .'PENTING: Jawablah dengan SANGAT SINGKAT, PADAT, dan langsung pada intinya (maksimal 1-2 paragraf pendek, total maksimal 100 kata). Hindari penjelasan yang bertele-tele atau pengulangan kata. '
            .'Jawaban Anda harus selesai sepenuhnya dan tidak terpotong di tengah kalimat. '
            ."Jika informasi tidak ada dalam konteks, jawablah dengan sopan bahwa Anda kurang mengetahui tentang hal tersebut dan arahkan pengguna untuk menghubungi admin WhatsApp kami.\n\n"
            ."KONTEKS SEKOLAH:\n".$context;

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
     * Call the Google Gemini native API.
     *
     * @throws \Exception
     */
    protected function callGeminiApi(string $model, string $apiKey, string $systemInstruction, string $query, array $chatHistory): string
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
                'maxOutputTokens' => 1000,
            ],
        ];

        $request = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(12);

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
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (! $text) {
            throw new \Exception('Gemini API returned an empty response candidate.');
        }

        return trim($text);
    }

    /**
     * Call OpenAI Chat Completions compatible API (for Groq, DeepSeek, OpenRouter).
     *
     * @throws \Exception
     */
    protected function callOpenAiCompatibleApi(string $url, string $model, string $apiKey, string $systemInstruction, string $query, array $chatHistory, array $extraHeaders = []): string
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
            'max_tokens' => 300,
        ];

        $headers = array_merge([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ], $extraHeaders);

        $request = Http::withHeaders($headers)->timeout(12);

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
        $text = $result['choices'][0]['message']['content'] ?? null;

        if (! $text) {
            throw new \Exception('API returned an empty response candidate.');
        }

        return trim($text);
    }

    /**
     * Gather relevant context from database according to selected topic and user query keywords.
     */
    public function gatherContext(string $topic, string $query): string
    {
        $contextParts = [];

        // 1. Load General School Info
        $site = SiteSetting::first();
        if ($site) {
            $contextParts[] = 'Nama Sekolah: '.($site->school_name ?? 'MA Muhammadiyah Limpung')."\n"
                .'Motto: '.($site->school_motto ?? '-')."\n"
                .'Alamat: '.($site->address ?? '-')."\n"
                .'Email Resmi: '.($site->school_email_official ?? $site->email ?? '-')."\n"
                .'Nomor Telepon: '.($site->phone ?? '-')."\n"
                .'WhatsApp Admin: '.($site->whatsapp ?? '-')."\n"
                .'Kepala Sekolah: '.($site->headmaster_name ?? '-').' (NIP: '.($site->headmaster_nip ?? '-').")\n"
                .'Profil Singkat: '.($site->about_short ?? '-');
        }

        // 2. Load Knowledge Base Entries (filtered by keywords to save tokens)
        $cleanQuery = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($query));
        $words = array_filter(explode(' ', $cleanQuery), function ($word) {
            $word = trim($word);
            $stopWords = ['yang', 'dan', 'untuk', 'atau', 'ini', 'itu', 'saya', 'anda', 'kami', 'kita', 'mereka', 'dia', 'adalah', 'yaitu', 'yakni', 'pada', 'ke', 'dari', 'di', 'dengan', 'secara', 'oleh', 'karena', 'sehingga', 'maka', 'namun', 'tetapi', 'saja', 'juga', 'pun', 'ada', 'bisa', 'dapat', 'akan', 'telah', 'sudah', 'belum', 'baru', 'hanya', 'sangat', 'amat', 'paling', 'lebih', 'kurang', 'seperti', 'bagai', 'oleh', 'tentang', 'sebagai', 'apakah', 'bagaimana', 'kapan', 'siapa', 'mengapa', 'berapa', 'dimana', 'ke mana', 'dari mana', 'halo', 'hai', 'tanya', 'dong', 'sih', 'kah'];

            return strlen($word) >= 3 && ! in_array($word, $stopWords);
        });

        $knowledgeQuery = ChatbotKnowledgeBase::where('is_active', true)
            ->where(function ($q) use ($topic) {
                $q->where('topic', $topic)
                    ->orWhere('topic', 'umum');
            });

        if (! empty($words)) {
            $knowledgeQuery->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('title', 'like', "%{$word}%")
                        ->orWhere('content', 'like', "%{$word}%");
                }
            });
        }

        // Limit to max 3 entries for context efficiency
        $knowledge = $knowledgeQuery->latest()->take(3)->get();

        // If no keyword matches found, load up to 2 general records as basic context
        if ($knowledge->isEmpty()) {
            $knowledge = ChatbotKnowledgeBase::where('is_active', true)
                ->where('topic', 'umum')
                ->latest()
                ->take(2)
                ->get();
        }

        foreach ($knowledge as $item) {
            $contextParts[] = "Topik Pengetahuan [{$item->topic}] - {$item->title}:\n{$item->content}";
        }

        // 3. Load Topic Specific Content
        if ($topic === 'ppdb') {
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
        } elseif ($topic === 'kegiatan') {
            // Load latest 4 articles/activities
            $articles = Article::where('status', 'published')
                ->latest('published_at')
                ->take(4)
                ->get(['judul', 'ringkasan', 'published_at']);

            $articleTexts = "ARTIKEL DAN KEGIATAN SEKOLAH TERBARU:\n";
            foreach ($articles as $art) {
                $date = $art->published_at ? $art->published_at->format('d M Y') : '';
                $articleTexts .= "- Judul: {$art->judul} ({$date})\n  Ringkasan: {$art->ringkasan}\n";
            }
            $contextParts[] = $articleTexts;

            // Load achievements
            $achievements = Prestasi::latest()
                ->take(4)
                ->get(['judul', 'kategori', 'tanggal', 'deskripsi']);

            if ($achievements->isNotEmpty()) {
                $achTexts = "PRESTASI SEKOLAH TERBARU:\n";
                foreach ($achievements as $ach) {
                    $date = $ach->tanggal ? $ach->tanggal->format('d M Y') : '';
                    $achTexts .= "- Prestasi: {$ach->judul} ({$ach->kategori} - {$date})\n  Detail: {$ach->deskripsi}\n";
                }
                $contextParts[] = $achTexts;
            }
        }

        return implode("\n\n=== SECTION ===\n\n", $contextParts);
    }
}
