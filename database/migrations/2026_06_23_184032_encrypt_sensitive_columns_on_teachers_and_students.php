<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Teachers — widen columns to store encrypted (base64) values
        Schema::table('teachers', function (Blueprint $table) {
            $table->text('no_telepon')->nullable()->change();
            $table->text('email')->nullable()->change();
        });

        // Students — widen columns to store encrypted (base64) values
        Schema::table('students', function (Blueprint $table) {
            $table->text('no_telepon')->nullable()->change();
            $table->text('email')->nullable()->change();
            $table->text('nama_ayah')->nullable()->change();
            $table->text('nama_ibu')->nullable()->change();
            $table->text('pekerjaan_ayah')->nullable()->change();
            $table->text('pekerjaan_ibu')->nullable()->change();
            $table->text('no_telepon_orang_tua')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('no_telepon', 20)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('no_telepon', 20)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('nama_ayah', 100)->nullable()->change();
            $table->string('nama_ibu', 100)->nullable()->change();
            $table->string('pekerjaan_ayah', 100)->nullable()->change();
            $table->string('pekerjaan_ibu', 100)->nullable()->change();
            $table->string('no_telepon_orang_tua', 20)->nullable()->change();
        });
    }
};
