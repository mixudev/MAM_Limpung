<?php

use App\Http\Controllers\Dashboard\UnifiedDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Unified dashboard route untuk semua role. Data yang ditampilkan
| dibatasi berdasarkan permission yang dimiliki user, menggunakan gates.
|
*/

// -------------------------------------------------------------------------
//  Unified Dashboard (One route for all roles with dedicated middleware)
// -------------------------------------------------------------------------
Route::middleware(['auth', 'active', 'check.dashboard.access'])->group(function () {
    Route::get('/dashboard', UnifiedDashboardController::class)->name('dashboard');
});

// -------------------------------------------------------------------------
//  Dynamic Modular Routes (e.g. PPDB, Profile, Courses, etc.)
// -------------------------------------------------------------------------
// Fitur-fitur spesifik per role tetap modular di folder dashboard/
// Mereka punya permission middleware mereka sendiri
Route::middleware(['auth', 'active'])->group(function () {
    foreach (glob(__DIR__.'/dashboard/*.php') as $routeFile) {
        require $routeFile;
    }
});
