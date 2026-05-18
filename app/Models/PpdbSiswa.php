<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpdbSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_siswas';

    protected $fillable = [
        'nomor_registrasi',
        'nama_lengkap',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nomor_hp',
        'email',
        'nama_ayah',
        'nama_ibu',
        'alamat_lengkap',
        'sekolah_asal',
        'ukuran_baju',
        'foto_siswa',
        'status',
        'catatan_admin',
        'submitted_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'submitted_at'  => 'datetime',
        ];
    }

    /**
     * Boot method to generate a unique random registration number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                // Generate a highly secure unique alphanumeric registration number
                // Example: PPDB-2026-K7A9X
                $number = 'PPDB-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
            } while (self::where('nomor_registrasi', $number)->exists());
            
            $model->nomor_registrasi = $number;
        });
    }

    // -----------------------------------------------------------------------
    //  Scopes
    // -----------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    /**
     * Ambil URL foto siswa dari storage.
     */
    public function fotoUrl(): string
    {
        return $this->foto_siswa
            ? asset('storage/' . $this->foto_siswa)
            : asset('images/default-avatar.png');
    }

    /**
     * Label status dalam bahasa Indonesia.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
            default    => 'Menunggu Verifikasi',
        };
    }

    /**
     * Warna badge untuk status (Tailwind CSS class).
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            'diterima' => 'green',
            'ditolak'  => 'red',
            default    => 'yellow',
        };
    }
}
