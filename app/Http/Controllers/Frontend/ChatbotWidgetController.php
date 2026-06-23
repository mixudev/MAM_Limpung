<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChatbotAnalytic;
use App\Models\ChatbotFaq;
use App\Models\ChatbotLog;
use App\Models\ChatbotMessage;
use App\Models\ChatbotSession;
use App\Models\SiteSetting;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotWidgetController extends Controller
{
    public function __construct()
    {
        $siteSettingsArray = cache()->remember('site_settings', 86400, function () {
            return SiteSetting::first()?->toArray();
        });

        $isChatbotActive = $siteSettingsArray['is_chatbot_active'] ?? true;

        if (! $isChatbotActive) {
            abort(response()->json(['error' => 'Chatbot is disabled.'], 403));
        }
    }

    /**
     * Get active FAQs, optionally filtered by topic.
     */
    public function getFaqs(Request $request): JsonResponse
    {
        $faqs = ChatbotFaq::where('is_active', true)
            ->orderBy('order', 'asc')
            ->take(5)
            ->get();

        return response()->json($faqs);
    }

    /**
     * Get session and message history based on a list of session UUIDs.
     */
    public function getHistory(Request $request): JsonResponse
    {
        $sessionIds = $request->input('session_ids', []);

        if (empty($sessionIds) || ! is_array($sessionIds)) {
            return response()->json([]);
        }

        $sessions = ChatbotSession::whereIn('id', $sessionIds)
            ->with(['messages'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sessions);
    }

    /**
     * Start a new chatbot session.
     */
    public function startSession(Request $request): JsonResponse
    {
        $session = ChatbotSession::create([
            'user_id' => auth()->id(),
            'user_ip' => $request->ip(),
        ]);

        // Load empty messages relation
        $session->setRelation('messages', collect());

        return response()->json($session);
    }

    /**
     * Send a user message and receive an AI response.
     */
    public function sendMessage(Request $request, ChatbotSession $session, ChatbotService $chatbotService): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessageText = $request->input('message');

        // 1. Save User Message
        $userMessage = ChatbotMessage::create([
            'session_id' => $session->id,
            'sender' => 'user',
            'message' => $userMessageText,
        ]);

        // 2. Fetch past 6 messages for conversational context
        $chatHistory = $session->messages()
            ->where('id', '<', $userMessage->id)
            ->orderBy('created_at', 'asc')
            ->take(6)
            ->get(['sender', 'message'])
            ->toArray();

        $startTime = microtime(true);
        $apiKeyUsedId = null;
        $tokensUsed = 0;

        // 3. Check if the message matches an active FAQ question (case-insensitive) for instant response
        $faqMatch = ChatbotFaq::where('is_active', true)
            ->where('question', 'like', trim($userMessageText))
            ->first();

        if ($faqMatch) {
            $botResponseText = $faqMatch->answer;
            $endTime = microtime(true);
            $responseTimeMs = (int) round(($endTime - $startTime) * 1000);

            ChatbotLog::create([
                'session_id' => $session->id,
                'level' => 'info',
                'message' => 'Chatbot memberikan respons instan berdasarkan pencocokan FAQ Cepat.',
                'payload' => [
                    'faq_id' => $faqMatch->id,
                    'question' => $faqMatch->question,
                    'query' => $userMessageText,
                ],
            ]);
        } else {
            // Request Answer from Service
            try {
                $aiResult = $chatbotService->askAI($userMessageText, $chatHistory);
                $botResponseText = $aiResult['text'];
                $apiKeyUsedId = $aiResult['api_key_used_id'];
                $tokensUsed = $aiResult['tokens_used'];

                ChatbotLog::create([
                    'session_id' => $session->id,
                    'api_key_id' => $apiKeyUsedId,
                    'level' => 'success',
                    'message' => 'Chatbot berhasil memberikan respons AI.',
                    'payload' => [
                        'query' => $userMessageText,
                        'response_preview' => mb_substr($botResponseText, 0, 100).'...',
                        'tokens_used' => $tokensUsed,
                    ],
                ]);
            } catch (\Exception $e) {
                $botResponseText = 'Maaf, asisten AI kami sedang tidak merespons atau mengalami kendala teknis saat ini. Anda dapat mencoba mengirim pesan kembali atau bisa langsung menghubungi admin sekolah kami melalui tombol WhatsApp Admin.';

                ChatbotLog::create([
                    'session_id' => $session->id,
                    'level' => 'error',
                    'message' => 'Chatbot gagal merespons permintaan pengguna: '.$e->getMessage(),
                    'payload' => [
                        'query' => $userMessageText,
                        'exception' => get_class($e),
                        'trace' => substr($e->getTraceAsString(), 0, 1000),
                    ],
                ]);
            }
            $endTime = microtime(true);
            $responseTimeMs = (int) round(($endTime - $startTime) * 1000);
        }

        // 4. Save Bot Message
        $botMessage = ChatbotMessage::create([
            'session_id' => $session->id,
            'sender' => 'bot',
            'message' => $botResponseText,
        ]);

        // 5. Log Analytics record
        ChatbotAnalytic::create([
            'session_id' => $session->id,
            'query' => $userMessageText,
            'response' => $botResponseText,
            'response_time_ms' => $responseTimeMs,
            'tokens_used' => $tokensUsed,
            'api_key_used_id' => $apiKeyUsedId,
        ]);

        return response()->json([
            'user_message' => $userMessage,
            'bot_message' => $botMessage,
        ]);
    }

    /**
     * Submit user feedback for the chatbot's response.
     */
    public function submitFeedback(Request $request, ChatbotSession $session): JsonResponse
    {
        $request->validate([
            'feedback' => 'required|string|in:like,dislike',
        ]);

        $analytic = ChatbotAnalytic::where('session_id', $session->id)
            ->latest('id')
            ->first();

        if ($analytic) {
            $analytic->update([
                'feedback' => $request->input('feedback'),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
