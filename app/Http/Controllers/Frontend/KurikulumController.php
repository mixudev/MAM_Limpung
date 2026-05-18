<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KurikulumController extends Controller
{
    public function index(): View
    {
        return view('front.kurikulum.index');
    }
}
