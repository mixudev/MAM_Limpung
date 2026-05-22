<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Super Admin Dashboard
 * Requires: role=super-admin  OR permission=access-super-admin-dashboard
 */
class SuperAdminDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load('roles', 'permissions');

        return view('dashboard.super-admin', [
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
