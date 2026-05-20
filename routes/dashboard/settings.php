<?php

use App\Http\Controllers\Dashboard\Settings\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('permission:access-admin-dashboard')
    ->group(function () {
        Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
    });
