<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();

            // --- Relasi ---
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // --- Konten ---
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->string('foto', 500)->nullable();

            // --- Klasifikasi ---
            $table->enum('tingkat', ['sekolah', 'kabupaten', 'provinsi', 'nasional', 'internasional']);
            $table->enum('jenis', ['akademik', 'non_akademik'])->default('akademik');
            $table->string('penyelenggara', 255)->nullable();

            // --- Pemenang ---
            $table->string('peraih', 255);
            $table->string('juara', 50)->nullable();

            // --- Waktu ---
            $table->year('tahun');
            $table->date('tanggal_prestasi')->nullable();

            // --- Status ---
            $table->boolean('is_featured')->default(false);

            // --- Audit ---
            $table->timestamps();
            $table->softDeletes();

            // --- Index ---
            $table->index('tingkat');
            $table->index('tahun');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
