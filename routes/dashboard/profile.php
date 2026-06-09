<?php

use App\Http\Controllers\Dashboard\Profile\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Profile Routes
|--------------------------------------------------------------------------
|
| Routes untuk edit profile user di dashboard.
| Accessible oleh semua authenticated users.
|
*/

Route::middleware(['auth', 'active'])->group(function () {
    Route::prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
        });
});
