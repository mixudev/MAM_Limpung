<?php

use App\Http\Controllers\Dashboard\GaleriController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    // -------------------------------------------------------------------------
    //  Gallery Management & Approval Routes
    // -------------------------------------------------------------------------
    Route::prefix('dashboard/galeri')
        ->name('admin.galeri.')
        ->group(function () {
            // CRUD
            Route::get('/', [GaleriController::class, 'index'])->name('index');
            Route::get('/create', [GaleriController::class, 'create'])->name('create');
            Route::post('/', [GaleriController::class, 'store'])->name('store');
            Route::get('/{galeri}', [GaleriController::class, 'show'])->name('show');
            Route::get('/{galeri}/edit', [GaleriController::class, 'edit'])->name('edit');
            Route::put('/{galeri}', [GaleriController::class, 'update'])->name('update');
            Route::delete('/{galeri}', [GaleriController::class, 'destroy'])->name('destroy');

            // Approvals (Admin/Super-Admin only)
            Route::post('/{galeri}/approve', [GaleriController::class, 'approve'])->name('approve');
            Route::post('/{galeri}/reject', [GaleriController::class, 'reject'])->name('reject');
        });

});
