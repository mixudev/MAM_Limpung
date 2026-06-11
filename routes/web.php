<?php

use App\Http\Controllers\Dashboard\Security\GoogleDriveOAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Entry Point
|--------------------------------------------------------------------------
|
| File ini hanya menjadi titik masuk (entry point). Semua route yang
| sudah dimodularisasi ada di file-file terpisah di folder routes/.
|
| Urutan include penting:
|   1. front.php    — halaman publik (tidak butuh auth)
|   2. auth.php     — login/logout web
|   3. dashboard.php — panel semua role (butuh auth)
|
*/

// Google Drive OAuth2 callback — intentionally outside auth middleware.
// Google redirects here after consent; identity is verified via state token in session.
// Throttle ditambahkan untuk mencegah brute force state token.
Route::get('/admin/security/google-drive/callback', [GoogleDriveOAuthController::class, 'handleCallback'])
    ->name('admin.security.google-drive.callback')
    ->middleware('throttle:10,1');

require __DIR__.'/front.php';
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/mobile_apps.php';

// Test routes — hanya aktif di lingkungan local
if (app()->environment('local')) {
    require __DIR__.'/test.php';
}
