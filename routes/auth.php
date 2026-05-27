<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Web)
|--------------------------------------------------------------------------
|
| Login dan logout untuk sesi web. API auth terpisah di routes/api.php.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post')->middleware('throttle:10,1');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Public Verification & Password Reset Routes (Signed)
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');

Route::get('/reset-password-direct/{id}/{token}', [AuthController::class, 'showDirectResetPassword'])
    ->name('password.reset.direct')
    ->middleware('signed');

Route::post('/reset-password-direct/{id}/{token}', [AuthController::class, 'handleDirectResetPassword'])
    ->name('password.update.direct')
    ->middleware('signed');
