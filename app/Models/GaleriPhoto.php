<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'galeri_id',
        'file_path',
        'tipe',
        'is_cover',
        'order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
            'order' => 'integer',
        ];
    }

    // -----------------------------------------------------------------------
    //  Relationships
    // -----------------------------------------------------------------------

    public function galeri(): BelongsTo
    {
        return $this->belongsTo(Galeri::class, 'galeri_id');
    }

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    /**
     * Get the image URL (locally uploaded file or external link).
     */
    public function imageUrl(): string
    {
        if ($this->tipe === 'link') {
            return $this->file_path;
        }

        return asset('storage/'.$this->file_path);
    }
}
