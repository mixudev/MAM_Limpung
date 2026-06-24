<?php

use App\Models\AcademicYear;
use App\Models\PpdbSetting;
use App\Models\RegistrationWave;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $general = PpdbSetting::getValue('general', [
            'is_open' => true,
            'tahun_ajaran' => (int) date('Y'),
        ]);

        $waves = PpdbSetting::getValue('waves', []);

        if (! empty($general)) {
            $yearValue = (int) ($general['tahun_ajaran'] ?? date('Y'));

            $academicYear = AcademicYear::firstOrCreate(
                ['year' => $yearValue],
                [
                    'name' => $yearValue.'/'.($yearValue + 1),
                    'is_active' => $general['is_open'] ?? true,
                ]
            );

            foreach ($waves as $wave) {
                RegistrationWave::firstOrCreate(
                    [
                        'academic_year_id' => $academicYear->id,
                        'slug' => $wave['id'] ?? Str::slug($wave['name']),
                    ],
                    [
                        'name' => $wave['name'],
                        'start_date' => $wave['start_date'],
                        'end_date' => $wave['end_date'] ?? null,
                        'is_active' => $wave['is_active'] ?? true,
                    ]
                );
            }

            PpdbSetting::where('key', 'general')->delete();
            PpdbSetting::where('key', 'waves')->delete();
        }
    }

    public function down(): void
    {
        PpdbSetting::setValue('general', [
            'is_open' => AcademicYear::where('is_active', true)->exists(),
            'tahun_ajaran' => (int) date('Y'),
        ]);

        $waves = RegistrationWave::all()->map(fn ($w) => [
            'id' => $w->slug,
            'name' => $w->name,
            'start_date' => $w->start_date->format('Y-m-d'),
            'end_date' => $w->end_date?->format('Y-m-d'),
            'is_active' => $w->is_active,
        ])->toArray();

        PpdbSetting::setValue('waves', $waves);
    }
};
