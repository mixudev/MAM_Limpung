<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'foto',
        'tingkat',
        'jenis',
        'penyelenggara',
        'peraih',
        'juara',
        'tahun',
        'tanggal_prestasi',
        'is_featured',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_prestasi' => 'date',
            'is_featured'      => 'boolean',
        ];
    }

    // -----------------------------------------------------------------------
    //  Relationships
    // -----------------------------------------------------------------------

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // -----------------------------------------------------------------------
    //  Scopes
    // -----------------------------------------------------------------------

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByTingkat($query, string $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    public function scopeByTahun($query, int $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    public function fotoUrl(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-prestasi.jpg');
    }

    /**
     * Label tingkat dalam bahasa Indonesia.
     */
    public function tingkatLabel(): string
    {
        return match ($this->tingkat) {
            'internasional' => 'Internasional',
            'nasional'      => 'Nasional',
            'provinsi'      => 'Provinsi',
            'kabupaten'     => 'Kabupaten/Kota',
            default         => 'Sekolah',
        };
    }
}
