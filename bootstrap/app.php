<?php

use App\Http\Middleware\CheckDashboardAccess;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        // -----------------------------------------------------------------
        //  Global middleware aliases
        // -----------------------------------------------------------------
        $middleware->alias([
            'active' => EnsureUserIsActive::class,
            'permission' => CheckPermission::class,
            'role' => CheckRole::class,
            'check.dashboard.access' => CheckDashboardAccess::class,

            // Spatie's built-in aliases (optional, for Spatie 6.x compatibility)
            // 'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            // 'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            // 'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // -----------------------------------------------------------------
        //  Sanctum stateful domains (for SPA cookie-based auth)
        //  Not needed for pure token-based API auth
        // -----------------------------------------------------------------
        // $middleware->statefulApi();

        // -----------------------------------------------------------------
        //  Web middleware group additions
        // -----------------------------------------------------------------
        $middleware->web(append: [
            SecurityHeaders::class,
        ]);

        // -----------------------------------------------------------------
        //  API middleware group
        // -----------------------------------------------------------------
        $middleware->api(prepend: [
            // API rate limiting is configured in RouteServiceProvider
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
