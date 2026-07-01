<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE prestasis MODIFY COLUMN tingkat ENUM('sekolah','kabupaten','kwarda','provinsi','nasional','internasional','umum') NOT NULL DEFAULT 'sekolah'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE prestasis MODIFY COLUMN tingkat ENUM('sekolah','kabupaten','provinsi','nasional','internasional') NOT NULL DEFAULT 'sekolah'");
    }
};
