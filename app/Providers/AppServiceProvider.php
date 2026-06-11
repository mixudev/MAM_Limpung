<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Implicitly grant "super-admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Custom rate limiter for PPDB submissions to support "daftar bersama"
        // in computer labs sharing the same public IP address.
        RateLimiter::for('ppdb-submit', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('nisn') ?: $request->ip());
        });

        // API Login rate limiter — dua lapisan:
        //   1. Per IP + email  → mencegah single-source brute force
        //   2. Per email saja  → mencegah distributed botnet attack
        RateLimiter::for('api-login', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip().'|'.Str::lower($request->input('email', ''))),
                Limit::perMinute(15)->by('api-email|'.Str::lower($request->input('email', ''))),
            ];
        });

        // Share site settings dynamically with caching
        // Skip jika tabel belum ada (misal saat migrate awal)
        if (! Schema::hasTable('cache') || ! Schema::hasTable('site_settings') || ! Schema::hasTable('system_logs')) {
            return;
        }

        $siteSettingsArray = Cache::remember('site_settings', 86400, function () {
            $settings = SiteSetting::first() ?? SiteSetting::create([
                'school_name' => 'MAM Limpung',
                'about_short' => 'MA Muhammadiyah Limpung adalah lembaga pendidikan Islam yang berkomitmen untuk membentuk generasi yang berakhlak mulia, cerdas, dan siap menghadapi tantangan masa depan dengan landasan nilai-nilai Islam dan kemajuan teknologi.',
                'email' => 'info@mamlimpung.sch.id',
                'phone' => '+62 21 1234 5678',
                'whatsapp' => '+62 812 3456 789',
                'address' => 'Jl. Cokronegoro No.34, Gepor, Limpung, Kabupaten Batang, Jawa Tengah 51271',
                'facebook_url' => 'https://facebook.com',
                'instagram_url' => 'https://instagram.com',
                'youtube_url' => 'https://youtube.com',
                'twitter_url' => 'https://twitter.com',
            ]);

            return $settings ? $settings->toArray() : null;
        });

        if ($siteSettingsArray) {
            View::share('siteSettings', (object) $siteSettingsArray);
        }
    }
}
