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
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->nullable()->constrained('chatbot_api_keys')->nullOnDelete();
            $table->uuid('session_id')->nullable();
            $table->foreign('session_id')->references('id')->on('chatbot_sessions')->nullOnDelete();
            $table->string('level')->default('info'); // info, warning, error, success
            $table->text('message');
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
    }
};
