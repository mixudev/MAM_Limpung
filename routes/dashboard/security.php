<?php

use App\Http\Controllers\Dashboard\Security\GoogleDriveOAuthController;
use App\Http\Controllers\Dashboard\Security\RolePermissionController;
use App\Http\Controllers\Dashboard\Security\SecuritySettingsController;
use App\Http\Controllers\Dashboard\Security\SystemLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('super-admin')
    ->name('super-admin.')
    ->middleware(['auth', 'active', 'permission:access-super-admin-dashboard'])
    ->group(function () {
        Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{role:name}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role:name}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');

        // System Logs
        Route::get('/logs', [SystemLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/activity/{systemLog}', [SystemLogController::class, 'showActivity'])->name('logs.activity.show');
        Route::get('/logs/failed-job/{id}', [SystemLogController::class, 'showFailedJob'])->name('logs.failed-job.show');
        Route::post('/logs/failed-job/{id}/retry', [SystemLogController::class, 'retryFailedJob'])->name('logs.failed-job.retry');
        Route::delete('/logs/failed-job/{id}', [SystemLogController::class, 'deleteFailedJob'])->name('logs.failed-job.destroy');
    });

Route::prefix('super-admin')
    ->name('admin.')
    ->middleware(['auth', 'active', 'permission:access-super-admin-dashboard'])
    ->group(function () {
        // Security — Credentials only
        Route::get('/security', [SecuritySettingsController::class, 'index'])->name('security.index');
        Route::post('/security/credentials', [SecuritySettingsController::class, 'updateCredentials'])->name('security.credentials.update');
        Route::post('/security/smtp/test', [SecuritySettingsController::class, 'testSmtpConnection'])->name('security.smtp.test');

        // Google Drive OAuth2
        Route::post('/security/google-drive/authorize', [GoogleDriveOAuthController::class, 'authorize'])->name('security.google-drive.authorize');
        Route::post('/security/google-drive/revoke', [GoogleDriveOAuthController::class, 'revoke'])->name('security.google-drive.revoke');

    });
