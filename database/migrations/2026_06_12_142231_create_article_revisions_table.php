<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_revisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('article_id')
                ->constrained()
                ->cascadeOnDelete();

            // Reviewer yang meminta revisi (admin/guru)
            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Nomor urut revisi per artikel (1, 2, 3, …)
            $table->unsignedSmallInteger('revision_number');

            // Catatan / instruksi revisi dari reviewer
            $table->text('notes');

            // Status penyelesaian revisi
            // pending  = belum direspon penulis
            // resolved = penulis sudah submit ulang (re-pending)
            $table->string('status', 20)->default('pending');

            // Kapan penulis merespons (submit ulang artikel)
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['article_id', 'revision_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_revisions');
    }
};
