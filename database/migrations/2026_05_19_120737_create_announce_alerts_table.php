<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announce_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('content')->nullable();
            $table->text('image')->nullable(); // Holds JSON array of image file paths
            $table->string('action_url', 500)->nullable();
            $table->string('action_text', 100)->nullable();
            $table->string('popup_size', 20)->default('md'); // sm, md, lg, xl
            $table->string('display_frequency', 50)->default('once_per_session'); // once_per_session, every_load
            $table->string('target_page', 50)->default('home_only'); // all_pages, home_only, ppdb_only
            $table->boolean('is_active')->default(false);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'target_page']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announce_alerts');
    }
};
