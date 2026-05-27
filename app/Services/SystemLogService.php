<?php

namespace App\Services;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SystemLogService
{
    /**
     * Log an Eloquent model change event (create, update, delete).
     */
    public static function logModelEvent(string $event, Model $model): void
    {
        $user = Auth::user();

        $oldValues = [];
        $newValues = [];

        if ($event === 'updated') {
            $dirty = $model->getDirty();
            foreach ($dirty as $key => $value) {
                // Skip timestamps and sensitive fields
                if (in_array($key, ['updated_at', 'created_at', 'password', 'remember_token'])) {
                    continue;
                }
                $oldValues[$key] = $model->getOriginal($key);
                $newValues[$key] = $value;
            }

            // If no significant changes were made, do not log
            if (empty($newValues)) {
                return;
            }
        } elseif ($event === 'created') {
            $newValues = $model->attributesToArray();
            unset($newValues['password'], $newValues['remember_token']);
        } elseif ($event === 'deleted') {
            $oldValues = $model->attributesToArray();
            unset($oldValues['password'], $oldValues['remember_token']);
        }

        $modelName = class_basename($model);
        $description = "Membaca/mengubah data {$modelName}";
        if ($event === 'created') {
            $description = "Membuat data {$modelName} baru";
        } elseif ($event === 'updated') {
            $description = "Memperbarui data {$modelName}";
        } elseif ($event === 'deleted') {
            $description = "Menghapus data {$modelName}";
        }

        SystemLog::create([
            'user_id' => $user ? $user->id : null,
            'log_type' => 'activity',
            'event' => $event,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a security event.
     */
    public static function logSecurity(string $event, string $description, ?User $user = null): void
    {
        $resolvedUser = $user ?? Auth::user();

        SystemLog::create([
            'user_id' => $resolvedUser ? $resolvedUser->id : null,
            'log_type' => 'security',
            'event' => $event,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
