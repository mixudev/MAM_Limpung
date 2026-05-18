<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class JurusanController extends Controller
{
    public function index(): View
    {
        return view('front.jurusan.index');
    }
}
