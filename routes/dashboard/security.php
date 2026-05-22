<?php

use App\Http\Controllers\Dashboard\Security\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('super-admin')
    ->name('super-admin.')
    ->middleware(['auth', 'active', 'permission:access-super-admin-dashboard'])
    ->group(function () {
        Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{role:name}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role:name}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'active', 'permission:access-admin-dashboard'])
    ->group(function () {
        Route::get('/security', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'index'])->name('security.index');
        Route::post('/security/credentials', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'updateCredentials'])->name('security.credentials.update');
        Route::post('/security/backup/settings', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'updateBackupSettings'])->name('security.backup.settings');
        Route::post('/security/backup/generate-key', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'generateKey'])->name('security.backup.generate-key');
        Route::get('/security/backup/download-key', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'downloadKey'])->name('security.backup.download-key');
        Route::post('/security/backup/run', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'runBackup'])->name('security.backup.run');
        Route::get('/security/backup/download/{filename}', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'downloadBackup'])->name('security.backup.download');
        Route::delete('/security/backup/delete/{filename}', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'deleteBackup'])->name('security.backup.delete');
        Route::post('/security/backup/verify', [App\Http\Controllers\Dashboard\Security\SecuritySettingsController::class, 'verifyBackup'])->name('security.backup.verify');
    });

