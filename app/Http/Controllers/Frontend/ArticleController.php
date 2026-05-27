<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display the listing of published articles.
     */
    public function index(Request $request): View
    {
        $categoriesList = ArticleCategory::orderBy('name')->get();

        // Get all published articles with their category and author
        $articles = Article::published()
            ->with(['category', 'penulis'])
            ->latest('published_at')
            ->get();

        // Separate the headline (most recent published article) if it exists
        $headline = $articles->first();

        // Trending/Latest stack (excluding the headline)
        $latestArticles = $articles->slice(1, 4);

        // Map articles database collection to Alpine.js JSON-friendly format
        $articlesJson = $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->judul,
                'excerpt' => $article->ringkasan ?: str($article->konten)->stripTags()->limit(150),
                'category' => $article->category ? $article->category->name : 'Uncategorized',
                'date' => $article->published_at ? $article->published_at->translatedFormat('d M Y') : $article->created_at->translatedFormat('d M Y'),
                'image' => $article->thumbnailUrl(),
                'url' => route('frontend.article.show', $article->slug),
            ];
        })->values()->toJson();

        // Pass simple string array of categories to Alpine
        $categoriesJson = collect(['Semua'])
            ->concat($categoriesList->pluck('name'))
            ->toJson();

        return view('front.article.index', compact(
            'articles',
            'categoriesList',
            'headline',
            'latestArticles',
            'articlesJson',
            'categoriesJson'
        ));
    }

    /**
     * Display a specific published article.
     */
    public function show(Article $article): View
    {
        // Security check: Only allow viewing published articles unless the user is the author or admin
        if ($article->status !== 'published') {
            $user = auth()->user();
            $canPreview = $user && (
                $user->hasRole('admin') ||
                $user->hasRole('super-admin') ||
                $user->id === $article->user_id
            );

            if (! $canPreview) {
                abort(404, 'Artikel tidak ditemukan atau belum diterbitkan.');
            }
        }

        // Eager load the polymorphic SEO relations
        $article->load('seo');

        // Get read time estimation (roughly 200 words per minute)
        $wordCount = str_word_count(strip_tags($article->konten));
        $readTime = max(1, ceil($wordCount / 200));

        // Get related articles (same category, excluding current article)
        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('front.article.content', compact('article', 'readTime', 'relatedArticles'));
    }
}
