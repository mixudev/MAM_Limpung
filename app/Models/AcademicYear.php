<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'year',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function waves(): HasMany
    {
        return $this->hasMany(RegistrationWave::class, 'academic_year_id');
    }

    public function activeWaves(): HasMany
    {
        return $this->hasMany(RegistrationWave::class, 'academic_year_id')->where('is_active', true);
    }
}
