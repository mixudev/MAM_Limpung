<?php

use App\Http\Controllers\Dashboard\Announcement\AnnounceAdController;
use App\Http\Controllers\Dashboard\Announcement\AnnounceAlertController;
use App\Http\Controllers\Dashboard\Announcement\AnnouncementMainController;
use App\Http\Controllers\Dashboard\Announcement\AnnounceTextController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {

    Route::prefix('admin/announcements')
        ->name('admin.announcements.')
        ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
        ->group(function () {

            // Aggregator Index
            Route::get('/', [AnnouncementMainController::class, 'index'])->name('index');

            // Running Texts
            Route::prefix('texts')->name('texts.')->group(function () {
                Route::get('/create', [AnnounceTextController::class, 'create'])->name('create');
                Route::post('/', [AnnounceTextController::class, 'store'])->name('store');
                Route::get('/{announceText}/edit', [AnnounceTextController::class, 'edit'])->name('edit');
                Route::put('/{announceText}', [AnnounceTextController::class, 'update'])->name('update');
                Route::delete('/{announceText}', [AnnounceTextController::class, 'destroy'])->name('destroy');
                Route::post('/{announceText}/toggle-active', [AnnounceTextController::class, 'toggleActive'])->name('toggle-active');
            });

            // Popup Alerts
            Route::prefix('alerts')->name('alerts.')->group(function () {
                Route::get('/create', [AnnounceAlertController::class, 'create'])->name('create');
                Route::post('/', [AnnounceAlertController::class, 'store'])->name('store');
                Route::get('/{announceAlert}/edit', [AnnounceAlertController::class, 'edit'])->name('edit');
                Route::put('/{announceAlert}', [AnnounceAlertController::class, 'update'])->name('update');
                Route::delete('/{announceAlert}', [AnnounceAlertController::class, 'destroy'])->name('destroy');
                Route::post('/{announceAlert}/toggle-active', [AnnounceAlertController::class, 'toggleActive'])->name('toggle-active');
            });

            // Banner Ads
            Route::prefix('ads')->name('ads.')->group(function () {
                Route::get('/create', [AnnounceAdController::class, 'create'])->name('create');
                Route::post('/', [AnnounceAdController::class, 'store'])->name('store');
                Route::get('/{announceAd}/edit', [AnnounceAdController::class, 'edit'])->name('edit');
                Route::put('/{announceAd}', [AnnounceAdController::class, 'update'])->name('update');
                Route::delete('/{announceAd}', [AnnounceAdController::class, 'destroy'])->name('destroy');
                Route::post('/{announceAd}/toggle-active', [AnnounceAdController::class, 'toggleActive'])->name('toggle-active');
            });
        });

});
