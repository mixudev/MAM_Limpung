<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Galeri extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'judul',
        'deskripsi',
        'kategori',
        'tahun',
        'status',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'is_visible',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Use UUID as the route model binding key.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // -----------------------------------------------------------------------
    //  Relationships
    // -----------------------------------------------------------------------

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GaleriPhoto::class, 'galeri_id');
    }

    // -----------------------------------------------------------------------
    //  Scopes
    // -----------------------------------------------------------------------

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    /**
     * Get the cover photo.
     */
    public function cover()
    {
        $cover = $this->photos()->where('is_cover', true)->first();

        if (! $cover) {
            $cover = $this->photos()->orderBy('order')->orderBy('id')->first();
        }

        return $cover;
    }

    /**
     * Get the cover photo URL.
     */
    public function coverUrl(): string
    {
        $cover = $this->cover();

        return $cover ? $cover->imageUrl() : asset('images/default-thumbnail.jpg');
    }
}
