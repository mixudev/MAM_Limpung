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
        Schema::table('site_settings', function (Blueprint $table) {
            // Kepala Sekolah
            $table->string('headmaster_name', 255)->nullable()->after('logo_path')->comment('Nama kepala sekolah');
            $table->string('headmaster_nip', 50)->nullable()->after('headmaster_name')->comment('NIP kepala sekolah');
            $table->string('headmaster_phone', 50)->nullable()->after('headmaster_nip')->comment('Nomor telepon kepala sekolah');
            $table->string('headmaster_signature', 500)->nullable()->after('headmaster_phone')->comment('Tanda tangan digital kepala sekolah');

            // Data Sekolah
            $table->text('school_motto')->nullable()->after('headmaster_signature')->comment('Motto/semangat sekolah');
            $table->string('school_code', 50)->nullable()->after('school_motto')->comment('Kode sekolah (NPSN/NSSS)');
            $table->year('school_founding_year')->nullable()->after('school_code')->comment('Tahun berdiri sekolah');
            $table->enum('school_status', ['Negeri', 'Swasta'])->nullable()->after('school_founding_year')->comment('Status sekolah');
            $table->string('school_accreditation', 10)->nullable()->after('school_status')->comment('Akreditasi (A/B/C)');
            $table->string('school_website', 255)->nullable()->after('school_accreditation')->comment('Website sekolah');
            $table->string('school_email_official', 255)->nullable()->after('school_website')->comment('Email resmi sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Kepala Sekolah
            $table->dropColumn('headmaster_name');
            $table->dropColumn('headmaster_nip');
            $table->dropColumn('headmaster_phone');
            $table->dropColumn('headmaster_signature');

            // Data Sekolah
            $table->dropColumn('school_motto');
            $table->dropColumn('school_code');
            $table->dropColumn('school_founding_year');
            $table->dropColumn('school_status');
            $table->dropColumn('school_accreditation');
            $table->dropColumn('school_website');
            $table->dropColumn('school_email_official');
        });
    }
};
