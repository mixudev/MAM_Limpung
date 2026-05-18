<?php

use App\Http\Controllers\Dashboard\Ppdb\AdminPpdbController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    Route::prefix('admin/ppdb')
        ->name('admin.ppdb.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {
            Route::get('/', [AdminPpdbController::class, 'index'])->name('index');
            Route::get('/{ppdbSiswa}', [AdminPpdbController::class, 'show'])->name('show');
            Route::post('/{ppdbSiswa}/verify', [AdminPpdbController::class, 'verify'])->name('verify');
            Route::post('/{ppdbSiswa}/reject', [AdminPpdbController::class, 'reject'])->name('reject');
        });

});
