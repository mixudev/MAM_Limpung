<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChatbotAnalytic;
use App\Models\ChatbotApiKey;
use App\Models\ChatbotFaq;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChatbotConfigController extends Controller
{
    /**
     * Render the chatbot usage guide page.
     */
    public function guide(): View
    {
        return view('dashboard.admin.chatbot.guide');
    }

    /**
     * Render the admin dashboard configuration view.
     */
    public function index(Request $request): View
    {
        // 1. Core Analytics Metrics
        $totalSessions = ChatbotSession::count();
        $totalQueries = ChatbotAnalytic::count();
        $avgResponseTime = (int) round(ChatbotAnalytic::avg('response_time_ms') ?? 0);

        $likes = ChatbotAnalytic::where('feedback', 'like')->count();
        $dislikes = ChatbotAnalytic::where('feedback', 'dislike')->count();
        $feedbackRatio = $likes + $dislikes > 0 ? round(($likes / ($likes + $dislikes)) * 100) : 100;

        // 2. Traffic over the past 7 days
        $traffic = ChatbotAnalytic::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Top asked questions
        $topQuestions = ChatbotAnalytic::select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        // 4. Topic distribution
        $topicStats = ChatbotAnalytic::select('topic', DB::raw('count(*) as count'))
            ->groupBy('topic')
            ->get();

        // 5. Data listings for configuration tabs
        $apiKeys = ChatbotApiKey::orderBy('created_at', 'desc')->get();
        $knowledgeBases = ChatbotKnowledgeBase::orderBy('topic')->orderBy('created_at', 'desc')->get();
        $faqs = ChatbotFaq::orderBy('topic')->orderBy('order')->get();

        // Paginate sessions history
        $sessions = ChatbotSession::with(['user'])
            ->withCount('messages')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.admin.chatbot.index', compact(
            'totalSessions',
            'totalQueries',
            'avgResponseTime',
            'feedbackRatio',
            'likes',
            'dislikes',
            'traffic',
            'topQuestions',
            'topicStats',
            'apiKeys',
            'knowledgeBases',
            'faqs',
            'sessions'
        ));
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

        return redirect()->route('admin.chatbot.index', ['tab' => 'apikeys'])
            ->with('success', 'API Key AI baru berhasil ditambahkan.');
    }

    public function updateApiKey(Request $request, ChatbotApiKey $apiKey): RedirectResponse
    {
        $rules = [
            'provider' => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ];

        // Only validate API key if field is not empty
        if ($request->filled('api_key')) {
            $rules['api_key'] = 'required|string|max:255';
        }

        $data = $request->validate($rules);
        if (! $request->filled('api_key')) {
            unset($data['api_key']);
        }

        $apiKey->update($data);

        return redirect()->route('admin.chatbot.index', ['tab' => 'apikeys'])
            ->with('success', 'API Key AI berhasil diperbarui.');
    }

    public function toggleApiKey(ChatbotApiKey $apiKey): RedirectResponse
    {
        $apiKey->update([
            'is_active' => ! $apiKey->is_active,
        ]);

        $status = $apiKey->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.chatbot.index', ['tab' => 'apikeys'])
            ->with('success', "API Key AI berhasil {$status}.");
    }

    public function destroyApiKey(ChatbotApiKey $apiKey): RedirectResponse
    {
        $apiKey->delete();

        return redirect()->route('admin.chatbot.index', ['tab' => 'apikeys'])
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
        ]);

        ChatbotKnowledgeBase::create($data);

        return redirect()->route('admin.chatbot.index', ['tab' => 'knowledge'])
            ->with('success', 'Pengetahuan baru berhasil ditambahkan ke basis data.');
    }

    public function updateKnowledge(Request $request, ChatbotKnowledgeBase $knowledge): RedirectResponse
    {
        $data = $request->validate([
            'topic' => 'required|string|in:umum,ppdb,kegiatan,bantuan',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $knowledge->update($data);

        return redirect()->route('admin.chatbot.index', ['tab' => 'knowledge'])
            ->with('success', 'Pengetahuan basis data berhasil diperbarui.');
    }

    public function destroyKnowledge(ChatbotKnowledgeBase $knowledge): RedirectResponse
    {
        $knowledge->delete();

        return redirect()->route('admin.chatbot.index', ['tab' => 'knowledge'])
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
        ]);

        ChatbotFaq::create($data);

        return redirect()->route('admin.chatbot.index', ['tab' => 'faqs'])
            ->with('success', 'FAQ cepat baru berhasil ditambahkan.');
    }

    public function updateFaq(Request $request, ChatbotFaq $faq): RedirectResponse
    {
        $data = $request->validate([
            'topic' => 'required|string|in:umum,ppdb,kegiatan,bantuan',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);

        $faq->update($data);

        return redirect()->route('admin.chatbot.index', ['tab' => 'faqs'])
            ->with('success', 'FAQ cepat berhasil diperbarui.');
    }

    public function destroyFaq(ChatbotFaq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.chatbot.index', ['tab' => 'faqs'])
            ->with('success', 'FAQ cepat berhasil dihapus.');
    }

    // ═════════════════════════════════════════════════════════════════════════
    //  HISTORY TRANSCRIPT VIEW
    // ═════════════════════════════════════════════════════════════════════════

    /**
     * Show session details and conversation transcript as JSON for administrative review.
     */
    public function showSession(ChatbotSession $session): JsonResponse
    {
        $session->load(['messages', 'user']);

        return response()->json($session);
    }
}
