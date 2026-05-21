<?php

use App\Models\PpdbSiswa;
use Database\Seeders\PpdbSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PpdbSettingSeeder::class);
});

test('frontend ppdb form preserves old input after validation failure', function () {
    PpdbSiswa::factory()->create([
        'nisn' => '1112223334',
        'email' => 'existing@example.com',
    ]);

    $photo = UploadedFile::fake()->image('foto.jpg', 100, 100);

    $payload = [
        'nama_lengkap' => 'Ahmad Santoso',
        'nisn' => '1112223334',
        'nomor_hp' => '081234567890',
        'email' => 'existing@example.com',
        'foto_siswa' => $photo,
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2010-01-15',
        'tempat_lahir' => 'Semarang',
        'nama_ayah' => 'Budi',
        'nama_ibu' => 'Siti',
        'alamat_lengkap' => 'Jl. Merdeka No. 1',
        'sekolah_asal' => 'SMP Negeri 1',
        'ukuran_baju' => 'L',
    ];

    $response = $this->post(route('frontend.ppdb.store'), $payload);

    $response->assertSessionHasErrors(['nisn', 'email']);

    $formResponse = $this->get(route('frontend.ppdb.form'));

    $formResponse->assertSuccessful()
        ->assertSee('Ahmad Santoso', false)
        ->assertSee('1112223334', false)
        ->assertSee('existing@example.com', false)
        ->assertSee('Tutup notifikasi', false);
});
