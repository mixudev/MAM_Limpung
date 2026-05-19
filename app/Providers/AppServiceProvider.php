<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Custom rate limiter for PPDB submissions to support "daftar bersama"
        // in computer labs sharing the same public IP address.
        RateLimiter::for('ppdb-submit', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('nisn') ?: $request->ip());
        });
    }
}
