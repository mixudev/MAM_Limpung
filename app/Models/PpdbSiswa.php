<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PpdbSiswa extends Model
{
    use HasFactory;
    use LogsActivity;
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
        'ttd_digital',
        'status',
        'catatan_admin',
        'additional_fields',
        'submitted_at',
        'registration_wave_id',
    ];

    /**
     * Sembunyikan field sensitif dari serialisasi JSON/array.
     * Mencegah data pribadi terekspos secara tidak sengaja via API atau response.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'ttd_digital',    // Token tanda tangan digital — jangan ekspos ke publik
        'catatan_admin',  // Catatan internal admin — bukan untuk publik
        'additional_fields', // Bisa mengandung path file dokumen sensitif
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'submitted_at' => 'datetime',
            'additional_fields' => 'array',
        ];
    }

    /**
     * Use UUID as the route model binding key (prevents ID enumeration).
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Boot method to generate a unique random registration number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID for safe route binding
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            do {
                // Generate a highly secure unique alphanumeric registration number
                // Example: PPDB-2026-K7A9X
                $number = 'PPDB-'.date('Y').'-'.strtoupper(Str::random(5));
            } while (self::where('nomor_registrasi', $number)->exists());

            $model->nomor_registrasi = $number;

            // Generate a secure unique digital signature barcode token
            if (empty($model->ttd_digital)) {
                $model->ttd_digital = 'MAM-SIG-'.strtoupper(Str::random(12));
            }
        });

        static::saved(function ($model) {
            if ($wave = $model->registrationWave) {
                Cache::forget("ppdb_stats_{$wave->academicYear->year}");
            } elseif ($model->submitted_at) {
                Cache::forget('ppdb_stats_'.$model->submitted_at->year);
            }
            Cache::forget('ppdb_available_years');
            Cache::forget('ppdb_is_open');
        });

        static::deleted(function ($model) {
            if ($wave = $model->registrationWave) {
                Cache::forget("ppdb_stats_{$wave->academicYear->year}");
            } elseif ($model->submitted_at) {
                Cache::forget('ppdb_stats_'.$model->submitted_at->year);
            }
            Cache::forget('ppdb_available_years');
            Cache::forget('ppdb_is_open');
        });
    }

    // -----------------------------------------------------------------------
    //  Relations
    // -----------------------------------------------------------------------

    public function registrationWave(): BelongsTo
    {
        return $this->belongsTo(RegistrationWave::class);
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
            ? asset('storage/'.$this->foto_siswa)
            : asset('images/default-avatar.png');
    }

    /**
     * Label status dalam bahasa Indonesia.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    /**
     * Warna badge untuk status (Tailwind CSS class).
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            'diterima' => 'green',
            'ditolak' => 'red',
            default => 'yellow',
        };
    }
}
