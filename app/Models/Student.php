<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'alamat_orang_tua',
        'no_telepon_orang_tua',
        'tanggal_masuk',
        'status',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
            'no_telepon' => 'encrypted',
            'email' => 'encrypted',
            'alamat' => 'encrypted',
            'nama_ayah' => 'encrypted',
            'nama_ibu' => 'encrypted',
            'pekerjaan_ayah' => 'encrypted',
            'pekerjaan_ibu' => 'encrypted',
            'alamat_orang_tua' => 'encrypted',
            'no_telepon_orang_tua' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
