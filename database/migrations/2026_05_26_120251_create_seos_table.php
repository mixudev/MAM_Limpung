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
        Schema::create('seos', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation
            $table->morphs('seoable');

            // Metadata SEO
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('focus_keyword', 100)->nullable();
            $table->string('canonical_url', 500)->nullable();

            // Robots directives
            $table->boolean('is_indexed')->default(true); // true = index, false = noindex
            $table->boolean('is_followed')->default(true); // true = follow, false = nofollow

            // Custom Social Media Override (Open Graph / Twitter Card)
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};
