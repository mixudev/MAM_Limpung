<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['teacher_category_id']);
            $table->dropColumn(['teacher_category_id', 'jabatan']);
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('teacher_category_id')->nullable()->constrained('teacher_categories')->nullOnDelete();
            $table->string('jabatan', 255)->nullable()->after('nama');
        });
    }
};
