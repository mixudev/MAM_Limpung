<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('front.profile.index');
    }
}
