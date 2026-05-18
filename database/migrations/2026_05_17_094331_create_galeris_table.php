<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();

            // --- Relasi ---
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // --- Konten ---
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->string('file_path', 500);
            $table->enum('tipe', ['foto', 'video'])->default('foto');

            // --- Metadata ---
            $table->string('kategori', 100)->nullable();
            $table->year('tahun')->nullable();

            // --- Status ---
            $table->boolean('is_visible')->default(true);

            // --- Audit ---
            $table->timestamps();
            $table->softDeletes();

            // --- Index ---
            $table->index(['tipe', 'is_visible']);
            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
