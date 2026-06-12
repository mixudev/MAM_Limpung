<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: CheckPermission
 *
 * Enforces Spatie permission checks on routes.
 * Prefer using this over role-based checks to stay granular and flexible.
 *
 * Usage:
 *   Route::middleware('permission:view-users')->get(...)
 *   Route::middleware('permission:edit-users|delete-users')->get(...)  // any of
 *   Route::middleware('permission:edit-users&delete-users')->get(...)  // all of
 *
 * Registration in bootstrap/app.php:
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->alias(['permission' => CheckPermission::class]);
 *   })
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (empty($permissions)) {
            return $next($request);
        }

        $user = $request->user();

        if (! $user) {
            return $this->deny($request, 'Unauthenticated.');
        }

        foreach ($permissions as $permissionSet) {
            // Pipe | means "any of these"
            if (str_contains($permissionSet, '|')) {
                $anyOf = explode('|', $permissionSet);
                if ($user->hasAnyPermission($anyOf)) {
                    return $next($request);
                }
            }
            // Ampersand & means "all of these"
            elseif (str_contains($permissionSet, '&')) {
                $allOf = explode('&', $permissionSet);
                if ($user->hasAllPermissions($allOf)) {
                    return $next($request);
                }
            }
            // Single permission
            elseif ($user->hasPermissionTo($permissionSet)) {
                return $next($request);
            }
        }

        return $this->deny(
            $request,
            403,
            'Anda tidak memiliki izin untuk mengakses resource ini.'
        );
    }

    protected function deny(Request $request, int $status, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $status);
        }

        // Jika request web
        abort($status, $message);
    }
}
