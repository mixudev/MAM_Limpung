<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDashboardAccess
{
    /**
     * Handle an incoming request.
     * Mengecek apakah user memiliki minimal satu dashboard permission.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dashboardPermissions = [
            'access-super-admin-dashboard',
            'access-admin-dashboard',
            'access-guru-dashboard',
            'access-siswa-dashboard',
        ];

        // Check jika user memiliki minimal satu dashboard permission
        if (! $request->user()->hasAnyPermission($dashboardPermissions)) {
            abort(403, 'Unauthorized access to dashboard');
        }

        return $next($request);
    }
}
