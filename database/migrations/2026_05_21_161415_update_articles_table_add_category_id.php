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
        Schema::table('articles', function (Blueprint $table) {
            // Drop old string column
            if (Schema::hasColumn('articles', 'kategori')) {
                $table->dropColumn('kategori');
            }

            // Add new category_id relationship
            $table->foreignId('category_id')
                ->after('user_id')
                ->nullable()
                ->constrained('article_categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Re-add old string column
            $table->string('kategori', 100)->nullable()->after('thumbnail');
        });
    }
};
