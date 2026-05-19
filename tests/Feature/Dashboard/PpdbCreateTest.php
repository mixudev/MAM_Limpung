<?php

use App\Jobs\SyncPpdbToGoogleSheetsJob;
use App\Models\PpdbSiswa;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('unauthenticated guest cannot access the ppdb create form or store data', function () {
    $response = $this->get(route('admin.ppdb.create'));
    $response->assertRedirect(route('login'));

    $responseStore = $this->post(route('admin.ppdb.store'), []);
    $responseStore->assertRedirect(route('login'));
});

test('unauthorized user cannot access the ppdb create form or store data', function () {
    $user = User::factory()->create();
    $user->assignRole('siswa');

    $response = $this->actingAs($user)->get(route('admin.ppdb.create'));
    $response->assertStatus(403);

    $responseStore = $this->actingAs($user)->post(route('admin.ppdb.store'), []);
    $responseStore->assertStatus(403);
});

test('authorized admin can load the create form page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.ppdb.create'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.create')
        ->assertSee('Tambah Pendaftar Baru')
        ->assertSee('Data Diri Calon Siswa');
});

test('authorized admin can submit and store a new student registration', function () {
    Queue::fake();

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $photo = UploadedFile::fake()->create('student_photo.jpg', 100);

    $data = [
        'nama_lengkap' => 'Muhammad Budi',
        'nisn' => '1234512345',
        'nomor_hp' => '08122334455',
        'email' => 'muhammad.budi@example.com',
        'foto_siswa' => $photo,
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2010-05-15',
        'tempat_lahir' => 'Batang',
        'nama_ayah' => 'Slamet',
        'nama_ibu' => 'Siti',
        'alamat_lengkap' => 'Jl. Pemuda No. 12, Limpung',
        'sekolah_asal' => 'SMP Negeri 1 Limpung',
        'ukuran_baju' => 'L',
        'status' => 'diterima',
        'catatan_admin' => 'Pendaftaran langsung via Admin dengan dokumen lengkap.',
    ];

    $response = $this->actingAs($admin)->post(route('admin.ppdb.store'), $data);

    $response->assertRedirect(route('admin.ppdb.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('ppdb_siswas', [
        'nama_lengkap' => 'Muhammad Budi',
        'nisn' => '1234512345',
        'email' => 'muhammad.budi@example.com',
        'status' => 'diterima',
        'catatan_admin' => 'Pendaftaran langsung via Admin dengan dokumen lengkap.',
    ]);

    Queue::assertPushed(SyncPpdbToGoogleSheetsJob::class);
});

test('registration validation fails for duplicate NISN or duplicate email', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Create an existing student
    PpdbSiswa::factory()->create([
        'nisn' => '9998887776',
        'email' => 'duplicate@example.com',
    ]);

    $data = [
        'nama_lengkap' => 'Budi Baru',
        'nisn' => '9998887776', // Duplicate NISN
        'nomor_hp' => '08122334455',
        'email' => 'duplicate@example.com', // Duplicate Email
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2010-05-15',
        'tempat_lahir' => 'Batang',
        'nama_ayah' => 'Slamet',
        'nama_ibu' => 'Siti',
        'alamat_lengkap' => 'Jl. Pemuda No. 12, Limpung',
        'sekolah_asal' => 'SMP Negeri 1 Limpung',
        'ukuran_baju' => 'L',
        'status' => 'diterima',
    ];

    $response = $this->actingAs($admin)->post(route('admin.ppdb.store'), $data);

    $response->assertSessionHasErrors(['nisn', 'email']);
});
