<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $kepalaSekolah = Teacher::with('categories')
            ->whereHas('categories', fn ($q) => $q->where('slug', 'kepala-madrasah'))
            ->where('status', 'aktif')
            ->first();

        return view('front.home.index', compact('kepalaSekolah'));
    }
}
