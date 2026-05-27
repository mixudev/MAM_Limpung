<?php

use App\Http\Controllers\Dashboard\Prestasi\AdminPrestasiController;
use App\Http\Controllers\Dashboard\Prestasi\AdminPrestasiIOController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    // -------------------------------------------------------------------------
    //  Prestasi CRUD & Import/Export (Admin / Super Admin)
    // -------------------------------------------------------------------------
    Route::prefix('admin/prestasi')
        ->name('admin.prestasi.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {
            // CRUD
            Route::get('/', [AdminPrestasiController::class, 'index'])->name('index');
            Route::get('/create', [AdminPrestasiController::class, 'create'])->name('create');
            Route::post('/', [AdminPrestasiController::class, 'store'])->name('store');
            Route::get('/{prestasi}/edit', [AdminPrestasiController::class, 'edit'])->name('edit');
            Route::put('/{prestasi}', [AdminPrestasiController::class, 'update'])->name('update');
            Route::delete('/{prestasi}', [AdminPrestasiController::class, 'destroy'])->name('destroy');
            Route::post('/upload-temp', [AdminPrestasiController::class, 'uploadTemp'])->name('upload-temp');

            // IO operations
            Route::get('/export/excel', [AdminPrestasiIOController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [AdminPrestasiIOController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/template', [AdminPrestasiIOController::class, 'downloadTemplate'])->name('template');
            Route::get('/import', [AdminPrestasiIOController::class, 'showImport'])->name('import.page');
            Route::post('/import', [AdminPrestasiIOController::class, 'importExcel'])->name('import');
            Route::post('/preview', [AdminPrestasiIOController::class, 'previewExcel'])->name('preview');
            Route::post('/save-preview', [AdminPrestasiIOController::class, 'saveFromPreview'])->name('save-preview');
        });

});
