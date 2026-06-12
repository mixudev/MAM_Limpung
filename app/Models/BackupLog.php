<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    protected $table = 'backup_logs';

    protected $fillable = [
        'filename',
        'type',
        'size',
        'encrypted',
        'status',
        'duration',
        'drive_uploaded',
        'drive_file_id',
        'drive_error',
        'error_message',
        'details',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'encrypted' => 'boolean',
            'drive_uploaded' => 'boolean',
            'duration' => 'float',
            'details' => 'array',
        ];
    }

    /**
     * Format file size in human-readable string.
     */
    public function getFormattedSizeAttribute(): string
    {
        if ($this->size <= 0) {
            return '-';
        }

        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2).' GB';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }

        return round($bytes / 1024, 2).' KB';
    }
}
