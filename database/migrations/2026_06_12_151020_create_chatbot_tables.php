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
        // 1. API Keys Table
        Schema::create('chatbot_api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('gemini'); // gemini, openai, etc.
            $table->string('model_name')->default('gemini-1.5-flash');
            $table->text('api_key'); // Will be encrypted using Crypt::encryptString()
            $table->boolean('is_active')->default(true);
            $table->integer('error_count')->default(0);
            $table->timestamp('limit_reached_at')->nullable();
            $table->timestamps();
        });

        // 2. Knowledge Base Table
        Schema::create('chatbot_knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('topic')->default('umum'); // ppdb, kegiatan, bantuan, umum
            $table->string('title');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. FAQs Table
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('topic')->default('umum'); // ppdb, kegiatan, bantuan, umum
            $table->string('question');
            $table->text('answer');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Chat Sessions Table (Using UUID as requested for saving/restoring chat history)
        Schema::create('chatbot_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_ip')->nullable();
            $table->string('topic')->default('umum'); // ppdb, kegiatan, bantuan, umum
            $table->timestamps();
        });

        // 5. Chat Messages Table
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id');
            $table->foreign('session_id')->references('id')->on('chatbot_sessions')->cascadeOnDelete();
            $table->enum('sender', ['user', 'bot']);
            $table->text('message');
            $table->timestamps();
        });

        // 6. Chatbot Analytics Table
        Schema::create('chatbot_analytics', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->nullable();
            $table->foreign('session_id')->references('id')->on('chatbot_sessions')->nullOnDelete();
            $table->text('query');
            $table->text('response')->nullable();
            $table->string('topic')->default('umum');
            $table->integer('response_time_ms')->default(0);
            $table->integer('tokens_used')->default(0);
            $table->foreignId('api_key_used_id')->nullable()->constrained('chatbot_api_keys')->nullOnDelete();
            $table->enum('feedback', ['like', 'dislike'])->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_analytics');
        Schema::dropIfExists('chatbot_messages');
        Schema::dropIfExists('chatbot_sessions');
        Schema::dropIfExists('chatbot_faqs');
        Schema::dropIfExists('chatbot_knowledge_bases');
        Schema::dropIfExists('chatbot_api_keys');
    }
};
