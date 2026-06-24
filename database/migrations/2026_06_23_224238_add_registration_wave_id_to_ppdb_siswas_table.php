<?php

use App\Models\RegistrationWave;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_siswas', function (Blueprint $table) {
            $table->foreignId('registration_wave_id')->nullable()->constrained()->nullOnDelete();
            $table->index('registration_wave_id');
        });

        // Backfill: match submitted_at against wave date ranges
        $waves = RegistrationWave::all();
        foreach ($waves as $wave) {
            $query = DB::table('ppdb_siswas')
                ->whereNull('registration_wave_id')
                ->whereNotNull('submitted_at')
                ->whereDate('submitted_at', '>=', $wave->start_date);

            if ($wave->end_date) {
                $query->whereDate('submitted_at', '<=', $wave->end_date);
            }

            $query->limit(500)->update(['registration_wave_id' => $wave->id]);
        }
    }

    public function down(): void
    {
        Schema::table('ppdb_siswas', function (Blueprint $table) {
            $table->dropForeign(['registration_wave_id']);
            $table->dropIndex(['registration_wave_id']);
            $table->dropColumn('registration_wave_id');
        });
    }
};
