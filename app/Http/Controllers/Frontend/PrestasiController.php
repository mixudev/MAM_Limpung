<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PrestasiController extends Controller
{
    public function index()
    {
        return view('front.prestasi.index');
    }
}
