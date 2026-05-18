<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return view('front.article.index');
    }
    public function show()
    {
        return view('front.article.content');
    }
}
