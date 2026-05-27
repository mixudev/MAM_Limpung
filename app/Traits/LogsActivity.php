<?php

namespace App\Traits;

use App\Services\SystemLogService;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Boot the trait to hook into Eloquent model events.
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            SystemLogService::logModelEvent('created', $model);
        });

        static::updated(function (Model $model) {
            SystemLogService::logModelEvent('updated', $model);
        });

        static::deleted(function (Model $model) {
            SystemLogService::logModelEvent('deleted', $model);
        });
    }
}
