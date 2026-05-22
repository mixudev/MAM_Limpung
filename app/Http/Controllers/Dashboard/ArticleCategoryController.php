<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreCategoryRequest;
use App\Http\Requests\Dashboard\UpdateCategoryRequest;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', ArticleCategory::class);

        $query = ArticleCategory::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
            );
        }

        $categories = $query->withCount('articles')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.admin.article-categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        // Validation and authorization are handled in StoreCategoryRequest
        ArticleCategory::create($request->validated());

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, ArticleCategory $category): RedirectResponse
    {
        // Validation and authorization are handled in UpdateCategoryRequest
        $category->update($request->validated());

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(ArticleCategory $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        $category->delete();

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil dihapus.');
    }
}
