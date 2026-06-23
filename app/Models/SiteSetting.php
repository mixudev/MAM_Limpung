<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'site_settings';

    protected $fillable = [
        'school_name',
        'logo_path',
        'about_short',
        'email',
        'phone',
        'whatsapp',
        'address',
        'google_maps_iframe',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'tiktok_url',
        'threads_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'google_analytics_id',
        'google_search_console_id',
        'google_tag_manager_id',
        'is_indexed',
        // Kepala Sekolah
        'headmaster_name',
        'headmaster_nip',
        'headmaster_phone',
        'headmaster_signature',
        // Data Sekolah
        'school_motto',
        'school_code',
        'school_founding_year',
        'school_status',
        'school_accreditation',
        'school_website',
        'school_email_official',
        'is_chatbot_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_indexed' => 'boolean',
            'is_chatbot_active' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('site_settings');
        });

        static::deleted(function () {
            Cache::forget('site_settings');
        });
    }
}
