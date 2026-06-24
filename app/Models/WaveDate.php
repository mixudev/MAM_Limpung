<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaveDate extends Model
{
    use LogsActivity;

    protected $fillable = [
        'registration_wave_id',
        'name',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function registrationWave(): BelongsTo
    {
        return $this->belongsTo(RegistrationWave::class);
    }
}
