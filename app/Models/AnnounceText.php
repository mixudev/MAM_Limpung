<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AnnounceText extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'announce_texts';

    protected $fillable = [
        'title',
        'content',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::saved(function () {
            Cache::forget('active_announcements_popups');
        });

        static::deleted(function () {
            Cache::forget('active_announcements_popups');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
