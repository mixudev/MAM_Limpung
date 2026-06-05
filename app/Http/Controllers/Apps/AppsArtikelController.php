<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\SystemLogService;
use App\Support\Security\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AppsArtikelController extends Controller
{
    /**
     * Display student's articles and upload form
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        $articles = Article::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->get();

        $categories = ArticleCategory::orderBy('name')->get();

        return view('mobile_apps.artikel.index', compact('articles', 'categories'));
    }

    /**
     * Show create form for student article
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        $categories = ArticleCategory::orderBy('name')->get();

        return view('mobile_apps.artikel.create', compact('categories'));
    }

    /**
     * Store a new student article
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:article_categories,id'],
            'ringkasan' => ['required', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul artikel wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'ringkasan.required' => 'Ringkasan wajib diisi.',
            'konten.required' => 'Konten artikel wajib diisi.',
            'thumbnail.required' => 'Thumbnail wajib diunggah.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
        ]);

        $kontenClean = HtmlSanitizer::clean($request->konten);

        $path = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('articles', $safeName, 'public');
        }

        $article = Article::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'judul' => $request->judul,
            'ringkasan' => $request->ringkasan,
            'konten' => $kontenClean,
            'thumbnail' => $path,
            'status' => 'pending', // Students upload as pending, admin will publish
            'published_at' => null,
        ]);

        // Create dummy SEO data
        $article->seo()->create([
            'meta_title' => $request->judul,
            'meta_description' => $request->ringkasan,
            'meta_keywords' => 'mam limpung, artikel siswa, '.$user->name,
            'is_indexed' => true,
            'is_followed' => true,
        ]);

        SystemLogService::logSecurity(
            'artikel_siswa_upload',
            "Siswa {$user->name} membuat artikel baru (menunggu konfirmasi): '{$article->judul}'",
            $user
        );

        return redirect()->route('apps.artikel')
            ->with('success', 'Berhasil!|Artikel berhasil diajukan dan sedang menunggu konfirmasi admin.');
    }

    /**
     * Display the specified article details
     */
    public function show(Request $request, Article $article): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $article->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        $article->load('category');

        return view('mobile_apps.artikel.show', compact('article'));
    }

    /**
     * Show edit form for student article
     */
    public function edit(Request $request, Article $article): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $article->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        $categories = ArticleCategory::orderBy('name')->get();

        return view('mobile_apps.artikel.edit', compact('article', 'categories'));
    }

    /**
     * Update the student article details
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $article->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:article_categories,id'],
            'ringkasan' => ['required', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul artikel wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'ringkasan.required' => 'Ringkasan wajib diisi.',
            'konten.required' => 'Konten artikel wajib diisi.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar thumbnail maksimal 2MB.',
        ]);

        $kontenClean = HtmlSanitizer::clean($request->konten);

        $data = [
            'category_id' => $request->category_id,
            'judul' => $request->judul,
            'ringkasan' => $request->ringkasan,
            'konten' => $kontenClean,
            'status' => 'pending', // Reset to pending for admin re-verification
        ];

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            $file = $request->file('thumbnail');
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('articles', $safeName, 'public');
            $data['thumbnail'] = $path;
        }

        $article->update($data);

        // Update SEO metadata
        $article->seo()->updateOrCreate(
            ['article_id' => $article->id],
            [
                'meta_title' => $request->judul,
                'meta_description' => $request->ringkasan,
                'meta_keywords' => 'mam limpung, artikel siswa, '.$user->name,
                'is_indexed' => true,
                'is_followed' => true,
            ]
        );

        SystemLogService::logSecurity(
            'artikel_siswa_update',
            "Siswa {$user->name} memperbarui artikel mereka (menunggu konfirmasi ulang): '{$article->judul}'",
            $user
        );

        return redirect()->route('apps.artikel')
            ->with('success', 'Berhasil!|Artikel berhasil diperbarui dan diajukan kembali ke admin.');
    }

    /**
     * Delete the student article
     */
    public function destroy(Request $request, Article $article): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $article->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        // Delete thumbnail from storage
        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        // Delete SEO record
        $article->seo()->delete();

        $judul = $article->judul;
        $article->delete();

        SystemLogService::logSecurity(
            'artikel_siswa_delete',
            "Siswa {$user->name} menghapus artikel mereka: '{$judul}'",
            $user
        );

        return redirect()->route('apps.artikel')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
