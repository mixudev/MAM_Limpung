<?php

use App\Http\Controllers\Dashboard\GaleriController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    // -------------------------------------------------------------------------
    //  Gallery Management Routes
    //  Permission diperlukan minimal: view-galeri, create-galeri, dll.
    //  Approval hanya bisa diakses oleh admin/super-admin.
    // -------------------------------------------------------------------------
    Route::prefix('dashboard/galeri')
        ->name('admin.galeri.')
        ->middleware('permission:view-galeri|access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {
            // CRUD — diizinkan semua yang punya permission galeri atau dashboard admin
            Route::get('/', [GaleriController::class, 'index'])->name('index');
            Route::get('/create', [GaleriController::class, 'create'])->name('create');
            Route::post('/', [GaleriController::class, 'store'])->name('store');
            Route::get('/{galeri}', [GaleriController::class, 'show'])->name('show');
            Route::get('/{galeri}/edit', [GaleriController::class, 'edit'])->name('edit');
            Route::put('/{galeri}', [GaleriController::class, 'update'])->name('update');
            Route::delete('/{galeri}', [GaleriController::class, 'destroy'])->name('destroy');
        });

    // Approvals — hanya Admin / Super Admin
    Route::prefix('dashboard/galeri')
        ->name('admin.galeri.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {
            Route::post('/{galeri}/approve', [GaleriController::class, 'approve'])->name('approve');
            Route::post('/{galeri}/reject', [GaleriController::class, 'reject'])->name('reject');
        });

});
