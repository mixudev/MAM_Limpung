<?php

use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\GuruDashboardController;
use App\Http\Controllers\Dashboard\SiswaDashboardController;
use App\Http\Controllers\Dashboard\SuperAdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Semua route panel per role. Setiap prefix dilindungi oleh permission
| berbasis Spatie Permission agar aman dan terpisah.
|
*/

Route::middleware(['auth', 'active'])->group(function () {

    // -------------------------------------------------------------------------
    //  Super Admin
    // -------------------------------------------------------------------------
    Route::prefix('super-admin')
        ->name('super-admin.')
        ->middleware('permission:access-super-admin-dashboard')
        ->group(function () {
            Route::get('/dashboard', SuperAdminDashboardController::class)->name('dashboard');

            // Super Admin custom security routes are defined in modular files
        });

    // -------------------------------------------------------------------------
    //  Admin
    // -------------------------------------------------------------------------
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('permission:access-admin-dashboard')
        ->group(function () {
            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

            Route::middleware('permission:view-courses')->group(function () {
                Route::get('/courses', fn () => view('placeholder', ['section' => 'Course Management']))->name('courses.index');
            });
        });

    // -------------------------------------------------------------------------
    //  Guru
    // -------------------------------------------------------------------------
    Route::prefix('guru')
        ->name('guru.')
        ->middleware('permission:access-guru-dashboard')
        ->group(function () {
            Route::get('/dashboard', GuruDashboardController::class)->name('dashboard');

            Route::middleware('permission:teach-courses')->group(function () {
                Route::get('/courses', fn () => view('placeholder', ['section' => 'My Courses']))->name('courses.index');
            });

            Route::middleware('permission:create-grades')->group(function () {
                Route::get('/grades', fn () => view('placeholder', ['section' => 'Grade Input']))->name('grades.index');
            });
        });

    // -------------------------------------------------------------------------
    //  Siswa
    // -------------------------------------------------------------------------
    Route::prefix('siswa')
        ->name('siswa.')
        ->middleware('permission:access-siswa-dashboard')
        ->group(function () {
            Route::get('/dashboard', SiswaDashboardController::class)->name('dashboard');

            Route::middleware('permission:attend-courses')->group(function () {
                Route::get('/courses', fn () => view('placeholder', ['section' => 'My Courses']))->name('courses.index');
            });

            Route::middleware('permission:view-own-grades')->group(function () {
                Route::get('/grades', fn () => view('placeholder', ['section' => 'My Grades']))->name('grades.index');
            });
        });

    // -------------------------------------------------------------------------
    //  Dynamic Modular Routes (e.g. PPDB, Profile, etc.)
    // -------------------------------------------------------------------------
    foreach (glob(__DIR__.'/dashboard/*.php') as $routeFile) {
        require $routeFile;
    }
});
