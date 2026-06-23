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
        Schema::create('file_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', 500);
            $table->string('file_hash', 64);
            $table->bigInteger('file_size')->default(0);
            $table->string('drive_file_id')->nullable();
            $table->string('sync_status', 20)->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique('file_path', 'file_sync_logs_path_unique');
            $table->index('sync_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_sync_logs');
    }
};
