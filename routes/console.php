<?php

use App\Models\SecuritySetting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Dynamic Automatic Backup Scheduler
try {
    $backupSettings = SecuritySetting::getValue('backup_settings', []);
    if (! empty($backupSettings['enabled'])) {
        $scheduleVal = $backupSettings['schedule'] ?? 'daily';

        // Validasi cron expression sebelum digunakan — mencegah DoS via custom expression
        // Hanya izinkan format cron 5-field standar (mencegah input berbahaya dari DB)
        $cronExpression = $backupSettings['cron_expression'] ?? '0 0 * * *';
        $validCronPattern = '/^(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)\s+(\*|[0-9,\-\/]+)$/';
        if (! preg_match($validCronPattern, trim($cronExpression))) {
            $cronExpression = '0 0 * * *'; // Fallback ke daily midnight jika invalid
        }

        $event = Schedule::command('app:backup-run');

        if ($scheduleVal === 'daily') {
            $event->dailyAt('00:00');
        } elseif ($scheduleVal === 'weekly') {
            $event->weeklyOn(0, '00:00');
        } elseif ($scheduleVal === 'monthly') {
            $event->monthlyOn(1, '00:00');
        } elseif ($scheduleVal === 'custom') {
            $event->cron($cronExpression);
        }
    }
} catch (Exception) {
    // Ignore database errors during bootstrap (e.g., during tests when migrations haven't run yet)
}
