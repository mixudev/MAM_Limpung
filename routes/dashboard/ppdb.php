<?php

use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbController;
use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbExportController;
use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbGoogleSheetsController;
use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbPrintController;
use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbSettingController;
use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    Route::prefix('admin/ppdb')
        ->name('admin.ppdb.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {
            Route::get('/', [AdminPpdbController::class, 'index'])->name('index');
            Route::get('/create', [AdminPpdbController::class, 'create'])->name('create');
            Route::post('/', [AdminPpdbController::class, 'store'])->name('store');

            // Settings Panel
            Route::get('/settings', [AdminPpdbSettingController::class, 'edit'])->name('settings.edit');
            Route::post('/settings/general', [AdminPpdbSettingController::class, 'updateGeneral'])->name('settings.general');
            Route::post('/settings/requirements', [AdminPpdbSettingController::class, 'updateRequirements'])->name('settings.requirements');
            Route::post('/settings/fields', [AdminPpdbSettingController::class, 'updateFields'])->name('settings.fields.update');

            // Export Panel
            Route::get('/export', [AdminPpdbExportController::class, 'exportPage'])->name('export');
            Route::post('/export/download', [AdminPpdbExportController::class, 'downloadExport'])->name('export.download');

            // Google Sheets Integration
            Route::get('/google-sheets', [AdminPpdbGoogleSheetsController::class, 'edit'])->name('google-sheets.edit');
            Route::post('/google-sheets', [AdminPpdbGoogleSheetsController::class, 'update'])->name('google-sheets.update');
            Route::post('/google-sheets/test', [AdminPpdbGoogleSheetsController::class, 'testConnection'])->name('google-sheets.test');
            Route::post('/google-sheets/sync-now', [AdminPpdbGoogleSheetsController::class, 'syncNow'])->name('google-sheets.sync-now');

            // Student Management
            Route::get('/{ppdbSiswa}', [AdminPpdbController::class, 'show'])->name('show');
            Route::get('/{ppdbSiswa}/print', [AdminPpdbPrintController::class, 'print'])->name('print');
            Route::post('/{ppdbSiswa}/verify', [AdminPpdbVerificationController::class, 'verify'])->name('verify');
            Route::post('/{ppdbSiswa}/reject', [AdminPpdbVerificationController::class, 'reject'])->name('reject');
        });

});
