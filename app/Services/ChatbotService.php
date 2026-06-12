<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ChatbotApiKey;
use App\Models\ChatbotKnowledgeBase;
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
            throw new \Exception('Maaf, tidak ada API Key AI yang aktif atau tersedia saat ini.');
        }

        $response = null;
        $apiKeyUsed = null;
        $lastException = null;

        foreach ($apiKeys as $keyRecord) {
            try {
                $apiKeyUsed = $keyRecord;
                $responseText = $this->callGeminiApi($keyRecord, $context, $query, $chatHistory);

                // Success! Reset error count
                $keyRecord->update([
                    'error_count' => 0,
                    'limit_reached_at' => null,
                ]);

                return [
                    'text' => $responseText,
                    'api_key_used_id' => $keyRecord->id,
                    'tokens_used' => str_word_count($responseText) + str_word_count($context) + 100, // Estimate
                ];
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Chatbot API Key ID {$keyRecord->id} failed: ".$e->getMessage());

                // Update key status to log error
                $keyRecord->update([
                    'error_count' => $keyRecord->error_count + 1,
                    'limit_reached_at' => now(),
                ]);
            }
        }

        throw new \Exception('Maaf, semua API Key AI sedang mengalami limitasi kuota atau gangguan teknis: '.($lastException ? $lastException->getMessage() : 'Unknown Error'));
    }

    /**
     * Call the Gemini API.
     *
     * @throws \Exception
     */
    protected function callGeminiApi(ChatbotApiKey $apiKeyRecord, string $context, string $query, array $chatHistory): string
    {
        $model = $apiKeyRecord->model_name ?? 'gemini-1.5-flash';
        $apiKey = $apiKeyRecord->api_key;
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        // Structure system instructions
        $systemInstruction = 'Anda adalah Customer Service AI yang ramah, sopan, dan profesional dari MA Muhammadiyah Limpung. '
            .'Tugas Anda adalah membantu menjawab pertanyaan pengguna dengan berbasis PADA INFORMASI KONTEKS SEKOLAH yang disediakan di bawah ini. '
            .'PENTING: Jawablah secara ringkas, padat, ramah, dan langsung pada intinya (maksimal 2-3 paragraf pendek) layaknya percakapan customer service manusia di chat (hindari jawaban yang terlalu panjang atau bertele-tele). '
            ."Jika informasi tidak ada dalam konteks, jawablah dengan sopan bahwa Anda kurang mengetahui tentang hal tersebut dan arahkan pengguna untuk mengklik tombol 'Tanya WhatsApp Admin' untuk bertanya langsung ke pihak sekolah.\n\n"
            ."KONTEKS SEKOLAH:\n".$context;

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
                'maxOutputTokens' => 500,
            ],
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(10)->post($url, $payload);

        if ($response->failed()) {
            $errBody = $response->json();
            $errMsg = $errBody['error']['message'] ?? 'API Request Failed';
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

        // 2. Load Knowledge Base Entries (matching topic or general)
        $knowledge = ChatbotKnowledgeBase::where('is_active', true)
            ->where(function ($q) use ($topic) {
                $q->where('topic', $topic)
                    ->orWhere('topic', 'umum');
            })
            ->get();

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
