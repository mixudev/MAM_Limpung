<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreArticleRequest;
use App\Http\Requests\Dashboard\UpdateArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
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

        $query = Article::query()->with(['penulis', 'category']);

        // Tenancy Isolation: Guru can only see their own articles
        $user = $request->user();
        if (! $user->hasRole('admin') && ! $user->hasRole('super-admin')) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(fn ($q) => $q->where('judul', 'like', "%{$search}%")
                ->orWhere('ringkasan', 'like', "%{$search}%")
                ->orWhere('konten', 'like', "%{$search}%")
            );
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $articles = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = ArticleCategory::orderBy('name')->get();

        return view('dashboard.admin.articles.index', compact('articles', 'categories'));
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

        // If published, set published_at if not filled
        if ($data['status'] === 'published') {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diterbitkan/disimpan.');
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
        if ($data['status'] === 'published') {
            $data['published_at'] = $data['published_at'] ?? $article->published_at ?? now();
        } else {
            $data['published_at'] = null;
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
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
