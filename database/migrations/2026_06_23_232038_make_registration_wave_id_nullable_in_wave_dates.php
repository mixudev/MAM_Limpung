<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wave_dates', function (Blueprint $table) {
            $table->dropForeign(['registration_wave_id']);
            $table->unsignedBigInteger('registration_wave_id')->nullable()->change();
            $table->foreign('registration_wave_id')->references('id')->on('registration_waves')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wave_dates', function (Blueprint $table) {
            $table->dropForeign(['registration_wave_id']);
        });

        DB::table('wave_dates')->whereNull('registration_wave_id')->delete();

        Schema::table('wave_dates', function (Blueprint $table) {
            $table->unsignedBigInteger('registration_wave_id')->nullable(false)->change();
            $table->foreign('registration_wave_id')->references('id')->on('registration_waves')->cascadeOnDelete();
        });
    }
};
