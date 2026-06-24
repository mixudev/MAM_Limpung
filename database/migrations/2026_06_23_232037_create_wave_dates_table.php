<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wave_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_wave_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('date');
            $table->timestamps();
        });

        DB::table('registration_waves')->orderBy('id')->lazy()->each(function ($wave) {
            if ($wave->registration_date) {
                DB::table('wave_dates')->insert([
                    'registration_wave_id' => $wave->id,
                    'name' => 'Registrasi Ulang',
                    'date' => $wave->registration_date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if ($wave->entry_date) {
                DB::table('wave_dates')->insert([
                    'registration_wave_id' => $wave->id,
                    'name' => 'Tanggal Masuk',
                    'date' => $wave->entry_date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wave_dates');
    }
};
