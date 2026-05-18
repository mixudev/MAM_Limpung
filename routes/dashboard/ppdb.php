<?php

use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbController;
use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbExportController;
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

            // Settings Panel
            Route::get('/settings', [AdminPpdbSettingController::class, 'edit'])->name('settings.edit');
            Route::post('/settings/general', [AdminPpdbSettingController::class, 'updateGeneral'])->name('settings.general');
            Route::post('/settings/requirements', [AdminPpdbSettingController::class, 'updateRequirements'])->name('settings.requirements');
            Route::post('/settings/fields', [AdminPpdbSettingController::class, 'storeField'])->name('settings.fields.store');
            Route::delete('/settings/fields/{fieldId}', [AdminPpdbSettingController::class, 'destroyField'])->name('settings.fields.destroy');

            // Export Panel
            Route::get('/export', [AdminPpdbExportController::class, 'exportPage'])->name('export');
            Route::post('/export/download', [AdminPpdbExportController::class, 'downloadExport'])->name('export.download');

            // Student Management
            Route::get('/{ppdbSiswa}', [AdminPpdbController::class, 'show'])->name('show');
            Route::get('/{ppdbSiswa}/print', [AdminPpdbPrintController::class, 'print'])->name('print');
            Route::post('/{ppdbSiswa}/verify', [AdminPpdbVerificationController::class, 'verify'])->name('verify');
            Route::post('/{ppdbSiswa}/reject', [AdminPpdbVerificationController::class, 'reject'])->name('reject');
        });

});
