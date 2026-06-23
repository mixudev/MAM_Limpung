<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileSyncLog extends Model
{
    protected $table = 'file_sync_logs';

    protected $fillable = [
        'file_path',
        'file_hash',
        'file_size',
        'drive_file_id',
        'sync_status',
        'error_message',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'synced_at' => 'datetime',
        ];
    }

    public function getFormattedSizeAttribute(): string
    {
        if ($this->file_size <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($this->file_size, 1024));

        return round($this->file_size / pow(1024, $i), 2).' '.$units[$i];
    }
}
