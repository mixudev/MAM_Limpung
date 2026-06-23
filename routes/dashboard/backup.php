<?php

use App\Http\Controllers\Dashboard\Backup\BackupController;
use Illuminate\Support\Facades\Route;

Route::prefix('super-admin')
    ->name('admin.')
    ->middleware(['auth', 'active', 'permission:access-super-admin-dashboard'])
    ->group(function () {
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/settings', [BackupController::class, 'updateSettings'])->name('backup.settings');
        Route::post('/backup/run', [BackupController::class, 'runBackup'])->name('backup.run');
        Route::get('/backup/download/{filename}', [BackupController::class, 'downloadBackup'])->name('backup.download');
        Route::delete('/backup/delete/{filename}', [BackupController::class, 'deleteBackup'])->name('backup.delete');
        Route::post('/backup/verify', [BackupController::class, 'verifyBackup'])->name('backup.verify');
        Route::get('/backup/log/{id}', [BackupController::class, 'getLogDetails'])->name('backup.log-details');
        Route::get('/backup/progress', [BackupController::class, 'getProgress'])->name('backup.progress');
        Route::post('/backup/sync-settings', [BackupController::class, 'updateSyncSettings'])->name('backup.sync-settings');
        Route::post('/backup/sync-run', [BackupController::class, 'runStorageSync'])->name('backup.sync-run');
        Route::get('/backup/sync-progress', [BackupController::class, 'getSyncProgress'])->name('backup.sync-progress');
        Route::get('/backup/sync-logs', [BackupController::class, 'getSyncLogs'])->name('backup.sync-logs');
        Route::delete('/backup/sync-logs', [BackupController::class, 'clearSyncLogs'])->name('backup.sync-logs.clear');
    });
