<?php

use App\Http\Controllers\Dashboard\Backup\BackupController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'active', 'permission:access-admin-dashboard'])
    ->group(function () {
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/settings', [BackupController::class, 'updateSettings'])->name('backup.settings');
        Route::post('/backup/generate-key', [BackupController::class, 'generateKey'])->name('backup.generate-key');
        Route::post('/backup/download-key', [BackupController::class, 'downloadKey'])->name('backup.download-key');
        Route::post('/backup/run', [BackupController::class, 'runBackup'])->name('backup.run');
        Route::get('/backup/download/{filename}', [BackupController::class, 'downloadBackup'])->name('backup.download');
        Route::delete('/backup/delete/{filename}', [BackupController::class, 'deleteBackup'])->name('backup.delete');
        Route::post('/backup/verify', [BackupController::class, 'verifyBackup'])->name('backup.verify');
        Route::get('/backup/storage-directories', [BackupController::class, 'getStorageDirectories'])->name('backup.storage-directories');
        Route::get('/backup/log/{id}', [BackupController::class, 'getLogDetails'])->name('backup.log-details');
    });
