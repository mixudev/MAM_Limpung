<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Dynamic Automatic Backup Scheduler
try {
    $backupSettings = \App\Models\SecuritySetting::getValue('backup_settings', []);
    if (! empty($backupSettings['enabled'])) {
        $scheduleVal = $backupSettings['schedule'] ?? 'daily';
        $cronExpression = $backupSettings['cron_expression'] ?? '0 0 * * *';

        $event = \Illuminate\Support\Facades\Schedule::command('app:backup-run');

        if ($scheduleVal === 'daily') {
            $event->dailyAt('00:00');
        } elseif ($scheduleVal === 'weekly') {
            $event->weeklyOn(0, '00:00');
        } elseif ($scheduleVal === 'monthly') {
            $event->monthlyOn(1, '00:00');
        } elseif ($scheduleVal === 'custom' && ! empty($cronExpression)) {
            $event->cron($cronExpression);
        }
    }
} catch (\Exception) {
    // Ignore database errors during bootstrap (e.g., during tests when migrations haven't run yet)
}
