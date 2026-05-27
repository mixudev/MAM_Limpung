<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;
    use HasSeo;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'thumbnail',
        'status',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $article) {
            if (empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->judul);
            }
        });

        static::updating(function (self $article) {
            if ($article->isDirty('judul')) {
                $article->slug = static::generateUniqueSlug($article->judul, $article->id);
            }
        });

        static::saved(function () {
            Cache::forget('seo_sitemap_articles');
        });

        static::deleted(function () {
            Cache::forget('seo_sitemap_articles');
        });
    }

    /**
     * Generate unique slug for article
     */
    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $originalSlug.'-'.$count;
            $count++;
        }

        return $slug;
    }

    // -----------------------------------------------------------------------
    //  Route Model Binding — gunakan slug, bukan id
    // -----------------------------------------------------------------------

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -----------------------------------------------------------------------
    //  Relationships
    // -----------------------------------------------------------------------

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    // -----------------------------------------------------------------------
    //  Scopes
    // -----------------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    public function thumbnailUrl(): string
    {
        return $this->thumbnail
            ? asset('storage/'.$this->thumbnail)
            : asset('images/default-thumbnail.jpg');
    }
}
