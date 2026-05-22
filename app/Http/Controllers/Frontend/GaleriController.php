<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class GaleriController extends Controller
{
    public function index()
    {
        return view('front.galeri.index');
    }
}
