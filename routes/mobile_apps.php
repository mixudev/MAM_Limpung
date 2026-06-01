<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\AppsController;

Route::middleware(['auth', 'active'])->group(function () {

    foreach (glob(__DIR__ . '/mobile_apps/*.php') as $routeFile) {
        require $routeFile;
    }

    Route::prefix('apps')
        ->name('apps.')
        ->group(function () {
            Route::get('/', [AppsController::class, 'index'])->name('home');
        }); 

});