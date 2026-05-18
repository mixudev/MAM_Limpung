<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: CheckRole
 *
 * Guards routes by role. Use sparingly — prefer CheckPermission for
 * granular access control. Use CheckRole only for high-level
 * route grouping (e.g. admin panel sections).
 *
 * Usage:
 *   Route::middleware('role:super-admin')->get(...)
 *   Route::middleware('role:admin|super-admin')->get(...)  // any of
 *
 * Registration in bootstrap/app.php:
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->alias(['role' => CheckRole::class]);
 *   })
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (empty($roles)) {
            return $next($request);
        }

        $user = $request->user();

        if (! $user) {
            return $this->deny($request, 'Unauthenticated.');
        }

        foreach ($roles as $roleSet) {
            $anyRoles = explode('|', $roleSet);

            if ($user->hasAnyRole($anyRoles)) {
                return $next($request);
            }
        }

        return $this->deny($request, 'Anda tidak memiliki akses ke halaman ini.');
    }

    protected function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        abort(403, $message);
    }
}
