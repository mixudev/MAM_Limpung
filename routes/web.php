<?php

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

require __DIR__.'/front.php';
require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/test.php';
require __DIR__.'/mobile_apps.php';
