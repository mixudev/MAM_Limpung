<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AnnounceAd;
use App\Models\AnnounceAlert;
use App\Models\AnnounceText;
use App\Models\Article;
use App\Models\PpdbSiswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnifiedDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load('roles', 'permissions');

        // Kumpulkan stats berdasarkan permissions yang dimiliki user
        $stats = $this->getAccessibleStats($user);

        // Kumpulkan sections/features yang bisa diakses user
        $accessibleFeatures = $this->getAccessibleFeatures($user);

        return view('dashboard.index', [
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'stats' => $stats,
            'accessibleFeatures' => $accessibleFeatures,
        ]);
    }

    /**
     * Get stats yang accessible untuk user berdasarkan permissions
     */
    private function getAccessibleStats(mixed $user): array
    {
        $stats = [];

        // Super Admin & Admin: bisa lihat PPDB stats
        if ($user->can('access-super-admin-dashboard') || $user->can('access-admin-dashboard')) {
            $stats['total_ppdb'] = PpdbSiswa::count();
            $stats['ppdb_pending'] = PpdbSiswa::where('status', 'pending')->count();
            $stats['ppdb_diterima'] = PpdbSiswa::where('status', 'diterima')->count();
            $stats['ppdb_ditolak'] = PpdbSiswa::where('status', 'ditolak')->count();
            $stats['total_artikel'] = Article::count();
            $stats['total_pengumuman'] = AnnounceText::count() + AnnounceAlert::count() + AnnounceAd::count();
        }

        // Guru: bisa lihat stats tentang courses dan grades
        if ($user->can('access-guru-dashboard')) {
            $stats['total_courses'] = 0; // TODO: adjust based on your Courses model
            $stats['total_grades'] = 0; // TODO: adjust based on your Grades model
        }

        // Siswa: bisa lihat stats tentang enrolled courses dan grades
        if ($user->can('access-siswa-dashboard')) {
            $stats['enrolled_courses'] = 0; // TODO: adjust based on enrollment
            $stats['my_grades'] = 0; // TODO: adjust based on grades
        }

        return $stats;
    }

    /**
     * Get features yang accessible untuk user
     */
    private function getAccessibleFeatures(mixed $user): array
    {
        $features = [];

        // Helper function untuk safely get route
        $getRouteIfExists = function (string $routeName, string $default = '#'): string {
            try {
                return route($routeName);
            } catch (\Exception $e) {
                return $default;
            }
        };

        // Super Admin features
        if ($user->can('access-super-admin-dashboard')) {
            $features[] = [
                'name' => 'PPDB Management',
                'icon' => 'users',
                'url' => $getRouteIfExists('admin.ppdb.index'),
                'permission' => 'access-super-admin-dashboard',
            ];
            $features[] = [
                'name' => 'Security Settings',
                'icon' => 'shield',
                'url' => $getRouteIfExists('admin.settings.edit'),
                'permission' => 'access-super-admin-dashboard',
            ];
        }

        // Admin features
        if ($user->can('access-admin-dashboard')) {
            $features[] = [
                'name' => 'Article Management',
                'icon' => 'book',
                'url' => $getRouteIfExists('admin.articles.index'),
                'permission' => 'access-admin-dashboard',
            ];
            $features[] = [
                'name' => 'PPDB Management',
                'icon' => 'users',
                'url' => $getRouteIfExists('admin.ppdb.index'),
                'permission' => 'access-admin-dashboard',
            ];
        }

        // Guru features
        if ($user->can('access-guru-dashboard')) {
            $features[] = [
                'name' => 'Articles',
                'icon' => 'book',
                'url' => $getRouteIfExists('admin.articles.index'),
                'permission' => 'access-guru-dashboard',
            ];
        }

        // Siswa features - typically view only
        if ($user->can('access-siswa-dashboard')) {
            // Siswa features would go here
        }

        return $features;
    }
}
