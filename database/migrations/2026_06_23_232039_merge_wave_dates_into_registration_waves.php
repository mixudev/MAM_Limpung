<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->string('type', 20)->default('wave')->after('id');
            $table->unsignedBigInteger('academic_year_id')->nullable()->change();
        });

        foreach (DB::table('wave_dates')->orderBy('id')->get() as $wd) {
            $academicYearId = null;
            if ($wd->registration_wave_id) {
                $academicYearId = DB::table('registration_waves')
                    ->where('id', $wd->registration_wave_id)
                    ->value('academic_year_id');
            }

            DB::table('registration_waves')->insert([
                'type' => 'date',
                'academic_year_id' => $academicYearId,
                'slug' => Str::slug($wd->name),
                'name' => $wd->name,
                'start_date' => $wd->date,
                'end_date' => null,
                'is_active' => true,
                'created_at' => $wd->created_at ?? now(),
                'updated_at' => $wd->updated_at ?? now(),
            ]);
        }

        Schema::dropIfExists('wave_dates');
    }

    public function down(): void
    {
        Schema::create('wave_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_wave_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->date('date');
            $table->timestamps();
        });

        foreach (DB::table('registration_waves')->where('type', 'date')->orderBy('id')->get() as $row) {
            DB::table('wave_dates')->insert([
                'registration_wave_id' => null,
                'name' => $row->name,
                'date' => $row->start_date,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);
        }

        DB::table('registration_waves')->where('type', 'date')->delete();

        Schema::table('registration_waves', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('academic_year_id')->nullable(false)->change();
        });
    }
};
