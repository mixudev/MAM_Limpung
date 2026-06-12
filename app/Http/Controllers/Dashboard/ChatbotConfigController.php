<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChatbotAnalytic;
use App\Models\ChatbotApiKey;
use App\Models\ChatbotFaq;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotLog;
use App\Models\ChatbotSession;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChatbotConfigController extends Controller
{
    // ═════════════════════════════════════════════════════════════════════════
    //  SECTION PAGES
    // ═════════════════════════════════════════════════════════════════════════

    /**
     * Analytics overview page.
     */
    public function analytics(): View
    {
        $totalSessions = ChatbotSession::count();
        $totalQueries = ChatbotAnalytic::count();
        $avgResponseTime = (int) round(ChatbotAnalytic::avg('response_time_ms') ?? 0);

        $likes = ChatbotAnalytic::where('feedback', 'like')->count();
        $dislikes = ChatbotAnalytic::where('feedback', 'dislike')->count();
        $feedbackRatio = $likes + $dislikes > 0 ? round(($likes / ($likes + $dislikes)) * 100) : 100;

        $traffic = ChatbotAnalytic::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $topQuestions = ChatbotAnalytic::select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        $topicStats = ChatbotAnalytic::select('topic', DB::raw('count(*) as count'))
            ->groupBy('topic')
            ->get();

        // API usage stats: how many successful calls per API key (provider + model)
        $apiStats = ChatbotAnalytic::selectRaw('api_key_used_id, count(*) as total_calls, avg(response_time_ms) as avg_ms')
            ->whereNotNull('api_key_used_id')
            ->groupBy('api_key_used_id')
            ->orderByDesc('total_calls')
            ->with('apiKey')
            ->get();

        // Also count errors per provider from ChatbotLog
        $apiErrorStats = ChatbotLog::selectRaw('api_key_id, count(*) as total_errors')
            ->whereNotNull('api_key_id')
            ->where('level', 'warning')
            ->groupBy('api_key_id')
            ->pluck('total_errors', 'api_key_id');

        // Daily per-provider usage for line chart (last 7 days)
        $apiDailyStats = ChatbotAnalytic::selectRaw('DATE(created_at) as date, api_key_used_id, count(*) as count')
            ->whereNotNull('api_key_used_id')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date', 'api_key_used_id')
            ->orderBy('date', 'asc')
            ->with('apiKey')
            ->get();

        // Pre-map for safe @json usage in Blade (avoid arrow fn inside directives)
        $apiDailyRaw = $apiDailyStats->map(fn ($r) => [
            'date' => $r->date,
            'key_id' => $r->api_key_used_id,
            'count' => (int) $r->count,
            'provider' => strtoupper($r->apiKey->provider ?? 'UNKNOWN'),
            'model' => $r->apiKey->model_name ?? '?',
        ])->values();

        return view('dashboard.admin.chatbot.analytics', compact(
            'totalSessions', 'totalQueries', 'avgResponseTime',
            'feedbackRatio', 'likes', 'dislikes',
            'traffic', 'topQuestions', 'topicStats',
            'apiStats', 'apiErrorStats', 'apiDailyStats', 'apiDailyRaw'
        ));
    }

    /**
     * API Keys management page.
     */
    public function apikeyPage(): View
    {
        $apiKeys = ChatbotApiKey::orderBy('created_at', 'desc')->get();

        return view('dashboard.admin.chatbot.apikeys', compact('apiKeys'));
    }

    /**
     * Knowledge Base management page.
     */
    public function knowledgePage(): View
    {
        $knowledgeBases = ChatbotKnowledgeBase::orderBy('topic')->orderBy('created_at', 'desc')->get();

        return view('dashboard.admin.chatbot.knowledge', compact('knowledgeBases'));
    }

    /**
     * FAQs management page.
     */
    public function faqsPage(): View
    {
        $faqs = ChatbotFaq::orderBy('topic')->orderBy('order')->get();

        return view('dashboard.admin.chatbot.faqs', compact('faqs'));
    }

    /**
     * Chat history page.
     */
    public function historyPage(): View
    {
        $sessions = ChatbotSession::with(['user'])
            ->withCount('messages')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.admin.chatbot.history', compact('sessions'));
    }

    /**
     * Activity logs page.
     */
    public function logsPage(): View
    {
        $logs = ChatbotLog::with(['session', 'apiKey'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.admin.chatbot.logs', compact('logs'));
    }

    /**
     * Usage guide page.
     */
    public function guide(): View
    {
        return view('dashboard.admin.chatbot.guide');
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  API KEY CRUD
    // ═════════════════════════════════════════════════════════════════════════

    public function storeApiKey(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'provider' => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
            'api_key' => 'required|string|max:255',
        ]);

        ChatbotApiKey::create($data);

        return redirect()->route('admin.chatbot.apikeys')
            ->with('success', 'API Key AI baru berhasil ditambahkan.');
    }

    public function updateApiKey(Request $request, ChatbotApiKey $apiKey): RedirectResponse
    {
        $rules = [
            'provider' => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ];

        if ($request->filled('api_key')) {
            $rules['api_key'] = 'required|string|max:255';
        }

        $data = $request->validate($rules);

        if (! $request->filled('api_key')) {
            unset($data['api_key']);
        }

        $data['error_count'] = 0;
        $data['limit_reached_at'] = null;

        $apiKey->update($data);

        return redirect()->route('admin.chatbot.apikeys')
            ->with('success', 'API Key AI berhasil diperbarui dan status error telah di-reset.');
    }

    public function toggleApiKey(ChatbotApiKey $apiKey): RedirectResponse
    {
        $apiKey->update([
            'is_active' => ! $apiKey->is_active,
            'error_count' => 0,
            'limit_reached_at' => null,
        ]);

        $status = $apiKey->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.chatbot.apikeys')
            ->with('success', "API Key AI berhasil {$status} dan status error telah di-reset.");
    }

    public function destroyApiKey(ChatbotApiKey $apiKey): RedirectResponse
    {
        $apiKey->delete();

        return redirect()->route('admin.chatbot.apikeys')
            ->with('success', 'API Key AI berhasil dihapus.');
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  KNOWLEDGE BASE CRUD
    // ═════════════════════════════════════════════════════════════════════════

    public function storeKnowledge(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'topic' => 'required|string|in:umum,ppdb,kegiatan,bantuan',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        ChatbotKnowledgeBase::create($data);

        return redirect()->route('admin.chatbot.knowledge')
            ->with('success', 'Pengetahuan baru berhasil ditambahkan ke basis data.');
    }

    public function updateKnowledge(Request $request, ChatbotKnowledgeBase $knowledge): RedirectResponse
    {
        $data = $request->validate([
            'topic' => 'required|string|in:umum,ppdb,kegiatan,bantuan',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $knowledge->update($data);

        return redirect()->route('admin.chatbot.knowledge')
            ->with('success', 'Pengetahuan basis data berhasil diperbarui.');
    }

    public function destroyKnowledge(ChatbotKnowledgeBase $knowledge): RedirectResponse
    {
        $knowledge->delete();

        return redirect()->route('admin.chatbot.knowledge')
            ->with('success', 'Pengetahuan berhasil dihapus.');
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  FAQ CRUD
    // ═════════════════════════════════════════════════════════════════════════

    public function storeFaq(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'topic' => 'required|string|in:umum,ppdb,kegiatan,bantuan',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        ChatbotFaq::create($data);

        return redirect()->route('admin.chatbot.faqs')
            ->with('success', 'FAQ cepat baru berhasil ditambahkan.');
    }

    public function updateFaq(Request $request, ChatbotFaq $faq): RedirectResponse
    {
        $data = $request->validate([
            'topic' => 'required|string|in:umum,ppdb,kegiatan,bantuan',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $faq->update($data);

        return redirect()->route('admin.chatbot.faqs')
            ->with('success', 'FAQ cepat berhasil diperbarui.');
    }

    public function destroyFaq(ChatbotFaq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.chatbot.faqs')
            ->with('success', 'FAQ cepat berhasil dihapus.');
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  SESSION / TRANSCRIPT (JSON)
    // ═════════════════════════════════════════════════════════════════════════

    public function showSession(ChatbotSession $session): JsonResponse
    {
        $session->load(['messages', 'user']);

        return response()->json($session);
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  GLOBAL TOGGLE
    // ═════════════════════════════════════════════════════════════════════════

    public function toggleChatbot(Request $request): RedirectResponse
    {
        $siteSetting = SiteSetting::first();
        if ($siteSetting) {
            $siteSetting->update([
                'is_chatbot_active' => ! $siteSetting->is_chatbot_active,
            ]);

            Cache::forget('site_settings');
        }

        $status = ($siteSetting && $siteSetting->is_chatbot_active) ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "AI Chatbot berhasil {$status} secara global.");
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  LOGS
    // ═════════════════════════════════════════════════════════════════════════

    /**
     * Show detail page for a single log entry.
     */
    public function showLog(ChatbotLog $log): View
    {
        $log->load(['session', 'apiKey']);

        return view('dashboard.admin.chatbot.log_detail', compact('log'));
    }

    public function clearLogs(): RedirectResponse
    {
        ChatbotLog::truncate();

        return redirect()->route('admin.chatbot.logs')
            ->with('success', 'Semua log aktifitas chatbot berhasil dihapus.');
    }
}
