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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('google_analytics_id', 50)->nullable()->after('meta_description');
            $table->string('google_search_console_id', 100)->nullable()->after('google_analytics_id');
            $table->string('google_tag_manager_id', 50)->nullable()->after('google_search_console_id');
            $table->string('meta_keywords', 255)->nullable()->after('meta_description');
            $table->boolean('is_indexed')->default(true)->after('google_tag_manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_analytics_id',
                'google_search_console_id',
                'google_tag_manager_id',
                'meta_keywords',
                'is_indexed',
            ]);
        });
    }
};
