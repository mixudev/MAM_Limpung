<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\PpdbSiswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load('roles', 'permissions');

        $stats = [
            'total_ppdb' => PpdbSiswa::count(),
            'ppdb_pending' => PpdbSiswa::where('status', 'pending')->count(),
            'ppdb_diterima' => PpdbSiswa::where('status', 'diterima')->count(),
            'ppdb_ditolak' => PpdbSiswa::where('status', 'ditolak')->count(),
            'total_artikel' => Article::count(),
            'total_pengumuman' => AnnounceText::count() + AnnounceAlert::count() + AnnounceAd::count(),
        ];

        return view('dashboard.index', [
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'stats' => $stats,
        ]);
    }
}
