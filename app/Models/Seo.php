<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'canonical_url',
        'is_indexed',
        'is_followed',
        'og_title',
        'og_description',
        'og_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_indexed' => 'boolean',
            'is_followed' => 'boolean',
        ];
    }

    /**
     * Get the parent seoable model (Article, SiteSetting, etc.).
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
