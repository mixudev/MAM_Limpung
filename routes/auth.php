<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
/*
|--------------------------------------------------------------------------
| Authentication Routes (Web)
|--------------------------------------------------------------------------
|
| Login dan logout untuk sesi web. API auth terpisah di routes/api.php.
|
*/

use App\Http\Controllers\Auth\OtpAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post')->middleware('throttle:10,1');

    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:3,10');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

    // OTP Authentication Routes
    Route::get('/login-otp', [OtpAuthController::class, 'showRequestForm'])->name('login.otp');
    Route::post('/login-otp', [OtpAuthController::class, 'sendOtp'])->name('login.otp.send')->middleware('throttle:3,10');
    Route::get('/login-otp/verify', [OtpAuthController::class, 'showVerifyForm'])->name('login.otp.verify');
    Route::post('/login-otp/verify', [OtpAuthController::class, 'verify'])->name('login.otp.verify.post')->middleware('throttle:5,1');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Public Verification & Password Reset Routes (Signed)
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');

Route::get('/reset-password-direct/{uuid}/{token}', [AuthController::class, 'showDirectResetPassword'])
    ->name('password.reset.direct')
    ->middleware('signed');

Route::post('/reset-password-direct/{uuid}/{token}', [AuthController::class, 'handleDirectResetPassword'])
    ->name('password.update.direct')
    ->middleware('signed');
