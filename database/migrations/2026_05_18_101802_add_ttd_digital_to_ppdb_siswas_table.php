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
        Schema::table('ppdb_siswas', function (Blueprint $table) {
            $table->string('ttd_digital')->nullable()->after('foto_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_siswas', function (Blueprint $table) {
            $table->dropColumn('ttd_digital');
        });
    }
};
