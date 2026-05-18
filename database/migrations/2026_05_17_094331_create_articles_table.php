<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // --- Relasi ---
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // --- Konten ---
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('thumbnail', 500)->nullable();

            // --- Kategori & Tag ---
            $table->string('kategori', 100)->nullable();

            // --- Status Publikasi ---
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // --- Audit ---
            $table->timestamps();
            $table->softDeletes();

            // --- Index ---
            $table->index('status');
            $table->index('published_at');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
