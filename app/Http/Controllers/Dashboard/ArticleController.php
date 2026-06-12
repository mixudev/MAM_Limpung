<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreArticleRequest;
use App\Http\Requests\Dashboard\UpdateArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleRevision;
use App\Support\Security\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Article::class);

        $user = $request->user();
        $isAdmin = $user->hasRole('admin') || $user->hasRole('super-admin');

        // Base query dengan tenancy isolation
        $baseQuery = Article::query()->with(['penulis', 'category']);
        if (! $isAdmin) {
            $baseQuery->where('user_id', $user->id);
        }

        // Hitung per-tab untuk badge (clone query agar tidak saling mempengaruhi)
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'pending' => (clone $baseQuery)->whereIn('status', ['pending', 'revision'])->count(),
            'others' => (clone $baseQuery)->whereIn('status', ['draft', 'archived', 'rejected'])->count(),
        ];

        // Tab aktif — default 'all'
        $tab = $isAdmin ? $request->input('tab', 'all') : 'all';

        // Terapkan filter tab ke query utama
        $query = clone $baseQuery;
        match ($tab) {
            'published' => $query->where('status', 'published'),
            'pending' => $query->whereIn('status', ['pending', 'revision']),
            'others' => $query->whereIn('status', ['draft', 'archived', 'rejected']),
            default => null,
        };

        // Filter tambahan (search & category)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(fn ($q) => $q->where('judul', 'like', "%{$search}%")
                ->orWhere('ringkasan', 'like', "%{$search}%")
            );
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $articles = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = ArticleCategory::orderBy('name')->get();

        return view('dashboard.admin.articles.index', compact('articles', 'categories', 'counts', 'tab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Article::class);

        $categories = ArticleCategory::orderBy('name')->get();

        return view('dashboard.admin.articles.create', compact('categories'));
    }

    /**
     * Upload a temporary thumbnail for article creation/editing.
     */
    public function uploadTemp(Request $request): JsonResponse
    {
        Gate::authorize('create', Article::class);

        $request->validate([
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');

            // Generate a safe random name
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('temp', $safeName, 'public');

            return response()->json([
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
            ]);
        }

        return response()->json(['error' => 'Berkas tidak ditemukan.'], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        // Authorization is handled in StoreArticleRequest

        $data = $request->validated();

        // Server-side assignment of user_id (prevents author tampering / IDOR)
        $data['user_id'] = $request->user()->id;

        // Clean content from XSS attacks using HtmlSanitizer
        $data['konten'] = HtmlSanitizer::clean($data['konten']);

        // Handle Secure File Upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');

            // Safe random filename to prevent path traversal and overwrite attacks
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('articles', $safeName, 'public');

            $data['thumbnail'] = $path;
        } elseif ($request->filled('temp_thumbnail')) {
            $tempPath = $request->input('temp_thumbnail');

            // Path traversal protection: must be strictly inside temp/ and contain no '..'
            if (str_starts_with($tempPath, 'temp/') && ! str_contains($tempPath, '..')) {
                if (Storage::disk('public')->exists($tempPath)) {
                    $filename = basename($tempPath);
                    $newPath = 'articles/'.$filename;
                    Storage::disk('public')->move($tempPath, $newPath);
                    $data['thumbnail'] = $newPath;
                }
            }
        }

        // Normalize publish_now / publish_custom → published, and set published_at
        if ($data['status'] === 'publish_now') {
            $data['status'] = 'published';
            $data['published_at'] = now();
        } elseif ($data['status'] === 'publish_custom') {
            $data['status'] = 'published';
            // published_at already validated and present (required_if:status,publish_custom)
        }

        // If published, set published_at if not filled
        if ($data['status'] === 'published') {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        $article = Article::create($data);

        // Save SEO data
        $article->seo()->create([
            'meta_title' => $request->input('seo_meta_title'),
            'meta_description' => $request->input('seo_meta_description'),
            'meta_keywords' => $request->input('seo_meta_keywords'),
            'focus_keyword' => $request->input('seo_focus_keyword'),
            'canonical_url' => $request->input('seo_canonical_url'),
            'is_indexed' => $request->boolean('seo_is_indexed', true),
            'is_followed' => $request->boolean('seo_is_followed', true),
        ]);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diterbitkan/disimpan.');
    }

    /**
     * Display the specified article for review / preview in dashboard.
     */
    public function show(Article $article): View
    {
        Gate::authorize('view', $article);

        $article->load(['penulis', 'category', 'revisions.reviewer']);

        return view('dashboard.admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        $categories = ArticleCategory::orderBy('name')->get();

        return view('dashboard.admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        // Authorization is handled in UpdateArticleRequest

        $data = $request->validated();

        $wasRevision = $article->status === 'revision';

        // Clean content from XSS attacks using HtmlSanitizer
        $data['konten'] = HtmlSanitizer::clean($data['konten']);

        // Handle Secure File Upload and old image deletion
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');

            // Delete old file if exists
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            // Safe random filename to prevent path traversal and overwrite attacks
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('articles', $safeName, 'public');

            $data['thumbnail'] = $path;
        } elseif ($request->filled('temp_thumbnail')) {
            $tempPath = $request->input('temp_thumbnail');

            // Path traversal protection: must be strictly inside temp/ and contain no '..'
            if (str_starts_with($tempPath, 'temp/') && ! str_contains($tempPath, '..')) {
                if (Storage::disk('public')->exists($tempPath)) {
                    // Delete old file if exists
                    if ($article->thumbnail) {
                        Storage::disk('public')->delete($article->thumbnail);
                    }

                    $filename = basename($tempPath);
                    $newPath = 'articles/'.$filename;
                    Storage::disk('public')->move($tempPath, $newPath);
                    $data['thumbnail'] = $newPath;
                }
            }
        }

        // Handle publication timestamp
        if ($data['status'] === 'publish_now') {
            $data['status'] = 'published';
            $data['published_at'] = now();
        } elseif ($data['status'] === 'publish_custom') {
            $data['status'] = 'published';
            // published_at already validated and present
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = $data['published_at'] ?? $article->published_at ?? now();
        } else {
            $data['published_at'] = null;
        }

        $article->update($data);

        // Update SEO data
        $article->seo()->updateOrCreate([], [
            'meta_title' => $request->input('seo_meta_title'),
            'meta_description' => $request->input('seo_meta_description'),
            'meta_keywords' => $request->input('seo_meta_keywords'),
            'focus_keyword' => $request->input('seo_focus_keyword'),
            'canonical_url' => $request->input('seo_canonical_url'),
            'is_indexed' => $request->boolean('seo_is_indexed', true),
            'is_followed' => $request->boolean('seo_is_followed', true),
        ]);

        if ($wasRevision) {
            $article->revisions()
                ->where('status', 'pending')
                ->update([
                    'status' => 'resolved',
                    'resolved_at' => now(),
                ]);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Approve the specified article (publish it).
     */
    public function approve(Article $article): RedirectResponse
    {
        Gate::authorize('update', $article);

        if (auth()->user()->hasRole('siswa')) {
            abort(403, 'Siswa tidak dapat menyetujui artikel.');
        }

        // Tandai semua revisi pending sebagai resolved
        $article->revisions()
            ->where('status', 'pending')
            ->update(['status' => 'resolved', 'resolved_at' => now()]);

        $article->update([
            'status' => 'published',
            'published_at' => now(),
            'rejection_reason' => null,
            'rejection_count' => 0,
        ]);

        return redirect()->route('admin.articles.show', $article->slug)
            ->with('success', "Artikel '{$article->judul}' berhasil disetujui dan diterbitkan.");
    }

    /**
     * Request revision — artikel dikembalikan ke penulis untuk diperbaiki.
     */
    public function requestRevision(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('update', $article);

        if (auth()->user()->hasRole('siswa')) {
            abort(403, 'Siswa tidak dapat meminta revisi.');
        }

        $request->validate([
            'revision_notes' => ['required', 'string', 'max:2000'],
        ], [
            'revision_notes.required' => 'Catatan revisi wajib diisi.',
            'revision_notes.max' => 'Catatan revisi maksimal 2000 karakter.',
        ]);

        // Nomor revisi berikutnya
        $nextNumber = ($article->revisions()->max('revision_number') ?? 0) + 1;

        ArticleRevision::create([
            'article_id' => $article->id,
            'reviewer_id' => auth()->id(),
            'revision_number' => $nextNumber,
            'notes' => $request->input('revision_notes'),
            'status' => 'pending',
        ]);

        $article->update([
            'status' => 'revision',
            'published_at' => null,
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.articles.show', $article->slug)
            ->with('success', "Revisi ke-{$nextNumber} diminta. Artikel dikembalikan ke penulis untuk diperbaiki.");
    }

    /**
     * Reject the specified article and send it back to draft with a reason.
     */
    public function reject(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('update', $article);

        if (auth()->user()->hasRole('siswa')) {
            abort(403, 'Siswa tidak dapat menolak artikel.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $article->increment('rejection_count');

        $article->update([
            'status' => 'rejected',
            'published_at' => null,
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->route('admin.articles.show', $article->slug)
            ->with('success', "Artikel '{$article->judul}' ditolak.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        // Standard delete (SoftDeletes is configured, so we keep the thumbnail file in storage.
        // It will be deleted only on permanent force deletion if implemented.)
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dipindahkan ke tempat sampah.');
    }
}
