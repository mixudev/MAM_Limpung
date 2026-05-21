<?php

use App\Models\PpdbSiswa;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Run core permission and role seeders
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('unauthenticated guest cannot access the ppdb admin panel', function () {
    $response = $this->get(route('admin.ppdb.index'));
    $response->assertRedirect(route('login'));
});

test('unauthorized user without access-admin-dashboard permission cannot access ppdb admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('siswa'); // Assign a low level role

    $response = $this->actingAs($user)->get(route('admin.ppdb.index'));
    $response->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can access the ppdb admin dashboard index and view stats', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Create mock candidates
    PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Ahmad Rofiq',
        'nisn' => '1234567890',
        'sekolah_asal' => 'SMP Negeri 1 Limpung',
        'status' => 'pending',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.ppdb.index'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.index')
        ->assertSee('Penerimaan Peserta Didik Baru')
        ->assertSee('Ahmad Rofiq')
        ->assertSee('SMP Negeri 1 Limpung');
});

test('authorized admin can view applicant JSON details via show route', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Zahra Aulia',
        'nisn' => '0987654321',
        'status' => 'pending',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->getJson(route('admin.ppdb.show', $student));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'nama_lengkap' => 'Zahra Aulia',
                'nisn' => '0987654321',
                'status' => 'pending',
            ],
        ]);
});

test('authorized admin can verify and accept an applicant', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Budi Santoso',
        'status' => 'pending',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.ppdb.verify', $student), [
        'catatan_admin' => 'Berkas lengkap dan terverifikasi.',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('ppdb_siswas', [
        'id' => $student->id,
        'status' => 'diterima',
        'catatan_admin' => 'Berkas lengkap dan terverifikasi.',
    ]);
});

test('authorized admin can reject an applicant with a valid custom reason', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Citra Lestari',
        'status' => 'pending',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.ppdb.reject', $student), [
        'catatan_admin' => 'Scan Kartu Keluarga buram dan tidak terbaca.',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('ppdb_siswas', [
        'id' => $student->id,
        'status' => 'ditolak',
        'catatan_admin' => 'Scan Kartu Keluarga buram dan tidak terbaca.',
    ]);
});

test('rejection validation fails if reason is empty or too short', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Dwi Cahyo',
        'status' => 'pending',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.ppdb.reject', $student), [
        'catatan_admin' => 'Info', // Too short (min 5 characters)
    ]);

    $response->assertSessionHasErrors('catatan_admin');
});

test('authorized admin can access print details route and see school Kop', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Eka Putra',
        'nisn' => '5556667778',
        'sekolah_asal' => 'SMP Muhammadiyah Limpung',
        'status' => 'diterima',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.ppdb.print', $student));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.print')
        ->assertSee('Madrasah Aliyah Muhammadiyah Limpung')
        ->assertSee('EKA PUTRA')
        ->assertSee('5556667778')
        ->assertSee('DITERIMA (TERVERIFIKASI)');
});

test('authorized admin can access the ppdb export page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.ppdb.export'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.export')
        ->assertSee('Export Rekapitulasi Data PPDB')
        ->assertSee('Microsoft Excel Spreadsheet')
        ->assertSee('Dokumen PDF / Cetak Ledger');
});

test('authorized admin can download export data as CSV/Excel', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Galih Pratama',
        'nisn' => '8888777766',
        'sekolah_asal' => 'SMP Negeri 2 Limpung',
        'status' => 'diterima',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.ppdb.export.download'), [
        'format' => 'excel',
        'tahun_ajaran' => (int) date('Y'),
        'status' => 'diterima',
        'fields' => ['nama_lengkap', 'nisn', 'sekolah_asal', 'status'],
    ]);

    $response->assertStatus(200);
    $response->assertHeader('Content-Disposition');

    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('attachment')
        ->toContain('LAPORAN_PPDB_MAM_LIMPUNG_')
        ->toContain('.xlsx');
});

test('authorized admin can view/print export data as PDF', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    PpdbSiswa::factory()->create([
        'nama_lengkap' => 'Indah Cahyani',
        'nisn' => '9999888877',
        'sekolah_asal' => 'MTs Negeri 1 Batang',
        'status' => 'diterima',
        'submitted_at' => now(),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.ppdb.export.download'), [
        'format' => 'pdf',
        'tahun_ajaran' => (int) date('Y'),
        'status' => 'diterima',
        'fields' => ['nama_lengkap', 'nisn', 'sekolah_asal', 'status'],
    ]);

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.export_pdf')
        ->assertSee('Indah Cahyani')
        ->assertSee('9999888877')
        ->assertSee('MTs Negeri 1 Batang')
        ->assertSee('Buku Ledger Pendaftaran')
        ->assertSee('Madrasah Aliyah Muhammadiyah Limpung', false);
});
