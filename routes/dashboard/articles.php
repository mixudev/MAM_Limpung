<?php

use App\Http\Controllers\Dashboard\ArticleCategoryController;
use App\Http\Controllers\Dashboard\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    // -------------------------------------------------------------------------
    //  Kategori Artikel CRUD (Hanya Admin / Super Admin)
    // -------------------------------------------------------------------------
    Route::prefix('admin/article-categories')
        ->name('admin.article-categories.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {
            Route::get('/', [ArticleCategoryController::class, 'index'])->name('index');
            Route::post('/', [ArticleCategoryController::class, 'store'])->name('store');
            Route::put('/{category}', [ArticleCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [ArticleCategoryController::class, 'destroy'])->name('destroy');
        });

    // -------------------------------------------------------------------------
    //  Artikel CRUD (Admin, Super Admin, dan Guru)
    // -------------------------------------------------------------------------
    Route::prefix('admin/articles')
        ->name('admin.articles.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard|access-guru-dashboard')
        ->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->name('index');
            Route::post('/upload-temp', [ArticleController::class, 'uploadTemp'])->name('upload-temp');
            Route::get('/create', [ArticleController::class, 'create'])->name('create');
            Route::post('/', [ArticleController::class, 'store'])->name('store');
            Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
            Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
            Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        });

});
