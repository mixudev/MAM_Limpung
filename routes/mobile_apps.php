<?php

use App\Http\Controllers\Apps\AppsArtikelController;
use App\Http\Controllers\Apps\AppsController;
use App\Http\Controllers\Apps\AppsGaleriController;
use App\Http\Controllers\Apps\AppsTugasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    Route::prefix('apps')
        ->name('apps.')
        ->group(function () {
            Route::get('/', [AppsController::class, 'index'])->name('home');

            // Galeri
            Route::get('/galeri', [AppsGaleriController::class, 'index'])->name('galeri');
            Route::get('/galeri/create', [AppsGaleriController::class, 'create'])->name('galeri.create');
            Route::post('/galeri', [AppsGaleriController::class, 'store'])->name('galeri.store');
            Route::get('/galeri/{galeri}', [AppsGaleriController::class, 'show'])->name('galeri.show');
            Route::get('/galeri/{galeri}/edit', [AppsGaleriController::class, 'edit'])->name('galeri.edit');
            Route::put('/galeri/{galeri}', [AppsGaleriController::class, 'update'])->name('galeri.update');
            Route::delete('/galeri/{galeri}', [AppsGaleriController::class, 'destroy'])->name('galeri.destroy');

            // Artikel
            Route::get('/artikel', [AppsArtikelController::class, 'index'])->name('artikel');
            Route::get('/artikel/create', [AppsArtikelController::class, 'create'])->name('artikel.create');
            Route::post('/artikel', [AppsArtikelController::class, 'store'])->name('artikel.store');
            Route::get('/artikel/{article}', [AppsArtikelController::class, 'show'])->name('artikel.show');
            Route::get('/artikel/{article}/edit', [AppsArtikelController::class, 'edit'])->name('artikel.edit');
            Route::put('/artikel/{article}', [AppsArtikelController::class, 'update'])->name('artikel.update');
            Route::delete('/artikel/{article}', [AppsArtikelController::class, 'destroy'])->name('artikel.destroy');

            // Tugas
            Route::get('/tugas', [AppsTugasController::class, 'index'])->name('tugas');

            // Profil
            Route::get('/profile', [AppsController::class, 'profile'])->name('profile');
            Route::post('/profile/update', [AppsController::class, 'updateProfile'])->name('profile.update');
            Route::post('/profile/password', [AppsController::class, 'sendResetLink'])->name('profile.password');
        });

});
