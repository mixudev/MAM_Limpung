<?php

use App\Models\PpdbSiswa;
use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Run core permission and role seeders
    $this->seed(\Database\Seeders\Auth\PermissionSeeder::class);
    $this->seed(\Database\Seeders\Auth\RoleSeeder::class);
});

test('unauthenticated guest cannot access the ppdb admin panel', function () {
    $response = $this->get(route('admin.ppdb.index'));
    $response->assertRedirect(route('login'));
});

test('unauthorized user without access-admin-dashboard permission cannot access ppdb admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('siswa'); // Assign a low level role

    $response = $this->actingAs($user)->get(route('admin.ppdb.index'));
    $response->assertStatus(403);
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

    $response = $this->actingAs($admin)->getJson(route('admin.ppdb.show', $student->id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'nama_lengkap' => 'Zahra Aulia',
                'nisn' => '0987654321',
                'status' => 'pending',
            ]
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

    $response = $this->actingAs($admin)->post(route('admin.ppdb.verify', $student->id), [
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

    $response = $this->actingAs($admin)->post(route('admin.ppdb.reject', $student->id), [
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

    $response = $this->actingAs($admin)->post(route('admin.ppdb.reject', $student->id), [
        'catatan_admin' => 'Info', // Too short (min 5 characters)
    ]);

    $response->assertSessionHasErrors('catatan_admin');
});
