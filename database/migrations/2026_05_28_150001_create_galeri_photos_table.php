<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galeri_id')->constrained('galeris')->cascadeOnDelete();
            $table->string('file_path', 500);
            $table->enum('tipe', ['file', 'link'])->default('file');
            $table->boolean('is_cover')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('galeri_id');
            $table->index('is_cover');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_photos');
    }
};
