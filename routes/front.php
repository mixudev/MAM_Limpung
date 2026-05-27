<?php

use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\EkstrakurikulerController;
use App\Http\Controllers\Frontend\GaleriController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JurusanController;
use App\Http\Controllers\Frontend\KurikulumController;
use App\Http\Controllers\Frontend\PpdbController;
use App\Http\Controllers\Frontend\PrestasiController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\SeoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes — Halaman Publik
|--------------------------------------------------------------------------
|
| Semua halaman yang bisa diakses tanpa login (halaman sekolah publik).
| Semua route diberi prefix name 'frontend.' untuk konsistensi.
|
*/

// Dynamic SEO Routes
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('frontend.seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('frontend.seo.robots');

Route::name('frontend.')->group(function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // -------------------------------------------------------------------------
    //  PPDB (Penerimaan Peserta Didik Baru)
    // -------------------------------------------------------------------------
    Route::prefix('ppdb')->name('ppdb.')->group(function () {
        Route::get('/', [PpdbController::class, 'index'])->name('index');
        Route::get('/daftar', [PpdbController::class, 'form'])->name('form');
        Route::post('/daftar', [PpdbController::class, 'store'])->name('store')->middleware('throttle:ppdb-submit');
        Route::get('/sukses/{ppdbSiswa:nomor_registrasi}', [PpdbController::class, 'success'])->name('success')->middleware('signed');
        Route::get('/verifikasi/{nomor_registrasi}', [PpdbController::class, 'verify'])->name('verify');
        Route::get('/status', [PpdbController::class, 'statusForm'])->name('status');
        Route::post('/status', [PpdbController::class, 'checkStatus'])->name('check-status')->middleware('throttle:10,1');
    });

    // -------------------------------------------------------------------------
    //  Artikel
    // -------------------------------------------------------------------------
    Route::prefix('artikel')->name('article.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/{article:slug}', [ArticleController::class, 'show'])->name('show');
    });

    // -------------------------------------------------------------------------
    //  Akademik
    // -------------------------------------------------------------------------
    Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan');
    Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum');
    Route::get('/ekstrakurikuler', [EkstrakurikulerController::class, 'index'])->name('ekstrakurikuler');

    // -------------------------------------------------------------------------
    //  Konten Sekolah
    // -------------------------------------------------------------------------
    Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi');
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
});

Route::get('/link', function () {
    return view('links.link');
})->name('link');

/*
|--------------------------------------------------------------------------
| Development Routes (local only)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/dev/test', fn () => view('test'))->name('dev.test');
}
