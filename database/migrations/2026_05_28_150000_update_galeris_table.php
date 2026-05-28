<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['file_path', 'tipe']);

            // Add new columns
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('tahun');
            $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejected_reason')->nullable()->after('approved_at');
        });

        // Generate UUIDs for existing rows (if any)
        DB::table('galeris')->orderBy('id')->each(function ($row) {
            DB::table('galeris')->where('id', $row->id)->update([
                'uuid' => (string) Str::uuid(),
            ]);
        });

        Schema::table('galeris', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('file_path', 500)->after('deskripsi');
            $table->enum('tipe', ['foto', 'video'])->default('foto')->after('file_path');

            $table->dropForeign(['approved_by']);
            $table->dropColumn(['uuid', 'status', 'approved_by', 'approved_at', 'rejected_reason']);
        });
    }
};
