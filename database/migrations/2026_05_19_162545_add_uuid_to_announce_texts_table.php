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
        Schema::table('announce_texts', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('announce_texts')->orderBy('id')->each(function ($row) {
            DB::table('announce_texts')->where('id', $row->id)->update([
                'uuid' => (string) Str::uuid(),
            ]);
        });

        Schema::table('announce_texts', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('announce_texts', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
