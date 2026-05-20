<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

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
        'meta_title',
        'meta_description',
    ];

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
