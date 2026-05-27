<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    public function index(): View
    {
        $prestasis = Prestasi::latest('tanggal_prestasi')->get();

        return view('front.prestasi.index', compact('prestasis'));
    }
}
