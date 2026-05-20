<?php

use App\Http\Controllers\Dashboard\Security\UserAccountController;
use Illuminate\Support\Facades\Route;

// Super Admin User Management Routes
Route::prefix('super-admin')
    ->name('super-admin.')
    ->middleware(['auth', 'active', 'permission:access-super-admin-dashboard'])
    ->group(function () {
        Route::resource('users', UserAccountController::class)->except(['create', 'edit']);
    });

// Admin User Management Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'active', 'permission:access-admin-dashboard'])
    ->group(function () {
        Route::resource('users', UserAccountController::class)->except(['create', 'edit']);
    });
