<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi', 50)->unique();

            // --- Data Diri ---
            $table->string('nama_lengkap', 255);
            $table->string('nisn', 20)->unique();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 255);
            $table->date('tanggal_lahir');

            // --- Kontak ---
            $table->string('nomor_hp', 15);
            $table->string('email', 255)->unique();

            // --- Data Orang Tua ---
            $table->string('nama_ayah', 255);
            $table->string('nama_ibu', 255);

            // --- Alamat & Sekolah ---
            $table->text('alamat_lengkap');
            $table->string('sekolah_asal', 255);

            // --- Perlengkapan ---
            $table->enum('ukuran_baju', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL']);

            // --- Foto — disimpan sebagai path storage, bukan blob ---
            $table->string('foto_siswa', 500)->nullable();

            // --- Status Pendaftaran ---
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();

            // --- Audit ---
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            // --- Index ---
            $table->index('status');
            $table->index('jenis_kelamin');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_siswas');
    }
};
