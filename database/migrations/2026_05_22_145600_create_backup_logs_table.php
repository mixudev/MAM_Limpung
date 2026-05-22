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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('type')->default('Full Backup'); // Full Backup, Database Only, Files Only
            $table->bigInteger('size')->default(0);
            $table->boolean('encrypted')->default(false);
            $table->string('status')->default('success'); // success, failed
            $table->float('duration')->default(0);
            $table->boolean('drive_uploaded')->default(false);
            $table->string('drive_file_id')->nullable();
            $table->text('drive_error')->nullable();
            $table->text('error_message')->nullable();
            $table->json('details')->nullable(); // Extra JSON details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
