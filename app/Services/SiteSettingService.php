<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingService
{
    protected array $settings = [];

    const CACHE_KEY = 'site_settings';

    const CACHE_TTL = 86400; // 24 jam

    public function __construct()
    {
        $this->load();
    }

    protected function load(): void
    {
        $this->settings = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $model = SiteSetting::first();

            return $model ? $model->toArray() : [];
        });
    }

    // Ambil satu nilai
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }

    // Ambil semua sebagai array
    public function all(): array
    {
        return $this->settings;
    }

    // Ambil sebagai object
    public function object(): object
    {
        return (object) $this->settings;
    }

    // Ambil spesifik keys saja
    public function only(array $keys): array
    {
        return array_intersect_key($this->settings, array_flip($keys));
    }

    // Clear cache (panggil saat update setting)
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
