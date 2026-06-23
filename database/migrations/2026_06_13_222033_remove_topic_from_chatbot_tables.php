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
        Schema::table('chatbot_knowledge_bases', function (Blueprint $table) {
            $table->dropColumn('topic');
        });

        Schema::table('chatbot_faqs', function (Blueprint $table) {
            $table->dropColumn('topic');
        });

        Schema::table('chatbot_sessions', function (Blueprint $table) {
            $table->dropColumn('topic');
        });

        Schema::table('chatbot_analytics', function (Blueprint $table) {
            $table->dropColumn('topic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_knowledge_bases', function (Blueprint $table) {
            $table->string('topic')->default('umum');
        });

        Schema::table('chatbot_faqs', function (Blueprint $table) {
            $table->string('topic')->default('umum');
        });

        Schema::table('chatbot_sessions', function (Blueprint $table) {
            $table->string('topic')->default('umum');
        });

        Schema::table('chatbot_analytics', function (Blueprint $table) {
            $table->string('topic')->default('umum');
        });
    }
};
