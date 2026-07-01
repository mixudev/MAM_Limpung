<?php

use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ChatbotWidgetController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\EkstrakurikulerController;
use App\Http\Controllers\Frontend\GaleriController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JurusanController;
use App\Http\Controllers\Frontend\KurikulumController;
use App\Http\Controllers\Frontend\PegawaiController;
use App\Http\Controllers\Frontend\PpdbController;
use App\Http\Controllers\Frontend\PrestasiController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\SeoController;
use App\Support\PpdbTempUploadManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

// PPDB Temp File Preview — hanya bisa diakses oleh session yang sama (session-gated)
// File disimpan di private disk; route ini menjadi satu-satunya cara preview
Route::get('/ppdb/temp-preview/{field}', function (string $field) {
    // Sanitasi field name
    $field = preg_replace('/[^a-zA-Z0-9_]/', '', $field);

    if (! PpdbTempUploadManager::has($field)) {
        abort(404);
    }

    $path = PpdbTempUploadManager::path($field);
    $allMeta = PpdbTempUploadManager::all();
    $mime = $allMeta[$field]['mime'] ?? 'application/octet-stream';

    $contents = Storage::disk('local')->get($path);
    if ($contents === null) {
        abort(404);
    }

    return response($contents, 200)->header('Content-Type', $mime);
})->name('ppdb.temp.preview');

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
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/selayang-pandang', [ProfileController::class, 'selayangPandang'])->name('selayang-pandang');
        Route::get('/visi-misi', [ProfileController::class, 'visiMisi'])->name('visi-misi');
        Route::get('/periodisasi-kepala', [ProfileController::class, 'periodisasiKepala'])->name('periodisasi-kepala');
        Route::get('/struktur-organisasi', [ProfileController::class, 'strukturOrganisasi'])->name('struktur-organisasi');
        Route::get('/program-madrasah', [ProfileController::class, 'programMadrasah'])->name('program-madrasah');
        Route::get('/mmc', [ProfileController::class, 'mmc'])->name('mmc');
    });
    Route::get('/kontak', [ContactController::class, 'index'])->name('contact');

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');

    // Chatbot Frontend Widget Routes
    Route::prefix('chatbot')->name('chatbot.')->group(function () {
        Route::get('/faqs', [ChatbotWidgetController::class, 'getFaqs'])->name('faqs');
        Route::post('/history', [ChatbotWidgetController::class, 'getHistory'])->name('history');
        Route::post('/sessions', [ChatbotWidgetController::class, 'startSession'])->name('sessions.start');
        Route::post('/sessions/{session}/send', [ChatbotWidgetController::class, 'sendMessage'])->name('sessions.send')->middleware('throttle:30,1');
        Route::post('/sessions/{session}/feedback', [ChatbotWidgetController::class, 'submitFeedback'])->name('sessions.feedback');
    });
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
