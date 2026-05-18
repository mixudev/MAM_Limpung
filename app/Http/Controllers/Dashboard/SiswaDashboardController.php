<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.siswa', [
            'user'        => $request->user(),
            'permissions' => $request->user()->getAllPermissions()->pluck('name'),
        ]);
    }
}
