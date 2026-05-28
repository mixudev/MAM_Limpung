<?php

use App\Models\Galeri;
use App\Models\GaleriPhoto;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
    Storage::fake('public');
});

test('unauthenticated guest cannot access gallery dashboard', function () {
    $this->get(route('admin.galeri.index'))->assertRedirect(route('login'));
    $this->post(route('admin.galeri.store'), [])->assertRedirect(route('login'));
});

test('authorized admin can access gallery index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.galeri.index'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.galeri.index')
        ->assertSee('Kelola Galeri Foto');
});

test('admin can create a new gallery post which is automatically approved and visible', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $data = [
        'judul' => 'Album Admin Baru',
        'deskripsi' => 'Deskripsi album admin.',
        'kategori' => 'Belajar',
        'tahun' => 2026,
        'photos' => [
            UploadedFile::fake()->create('photo1.jpg', 100, 'image/jpeg'),
            UploadedFile::fake()->create('photo2.jpg', 100, 'image/jpeg'),
        ],
        'cover_type' => 'file',
        'cover_index' => 1,
    ];

    $response = $this->actingAs($admin)->post(route('admin.galeri.store'), $data);
    $response->assertRedirect(route('admin.galeri.index'));

    $galeri = Galeri::where('judul', 'Album Admin Baru')->first();
    expect($galeri)->not->toBeNull();
    expect($galeri->status)->toBe('approved');
    expect($galeri->is_visible)->toBeTrue();
    expect($galeri->photos->count())->toBe(2);

    // Check cover photo was set
    $coverPhoto = $galeri->photos()->where('is_cover', true)->first();
    expect($coverPhoto)->not->toBeNull();
    expect($coverPhoto->order)->toBe(1); // the 2nd photo (index 1)
});

test('student can create a new gallery post which defaults to pending and invisible', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $data = [
        'judul' => 'Album Siswa Baru',
        'deskripsi' => 'Deskripsi album siswa.',
        'kategori' => 'Ekskul',
        'tahun' => 2026,
        'photos' => [
            UploadedFile::fake()->create('siswa_photo.jpg', 100, 'image/jpeg'),
        ],
        'cover_type' => 'file',
        'cover_index' => 0,
    ];

    $response = $this->actingAs($siswa)->post(route('admin.galeri.store'), $data);
    $response->assertRedirect(route('admin.galeri.index'));

    $galeri = Galeri::where('judul', 'Album Siswa Baru')->first();
    expect($galeri)->not->toBeNull();
    expect($galeri->status)->toBe('pending');
    expect($galeri->is_visible)->toBeFalse();
});

test('admin can approve a pending gallery post', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $siswa = User::factory()->create();

    $galeri = Galeri::create([
        'user_id' => $siswa->id,
        'judul' => 'Pending Album',
        'deskripsi' => 'This is pending',
        'kategori' => 'Fasilitas',
        'tahun' => 2026,
        'status' => 'pending',
        'is_visible' => false,
    ]);

    GaleriPhoto::create([
        'galeri_id' => $galeri->id,
        'file_path' => 'galeri/test.jpg',
        'tipe' => 'file',
        'is_cover' => true,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.galeri.approve', $galeri->uuid));
    $response->assertRedirect();

    $galeri->refresh();
    expect($galeri->status)->toBe('approved');
    expect($galeri->is_visible)->toBeTrue();
    expect($galeri->approved_by)->toBe($admin->id);
});

test('admin can reject a pending gallery post with a reason', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $siswa = User::factory()->create();

    $galeri = Galeri::create([
        'user_id' => $siswa->id,
        'judul' => 'Pending Album 2',
        'deskripsi' => 'This is pending 2',
        'kategori' => 'Fasilitas',
        'tahun' => 2026,
        'status' => 'pending',
        'is_visible' => false,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.galeri.reject', $galeri->uuid), [
        'reason' => 'Gambar buram dan tidak sopan.',
    ]);
    $response->assertRedirect();

    $galeri->refresh();
    expect($galeri->status)->toBe('rejected');
    expect($galeri->is_visible)->toBeFalse();
    expect($galeri->rejected_reason)->toBe('Gambar buram dan tidak sopan.');
});

test('guest can view approved gallery in frontend page', function () {
    $admin = User::factory()->create();

    $galeri = Galeri::create([
        'user_id' => $admin->id,
        'judul' => 'Pameran Sekolah',
        'deskripsi' => 'Pameran karya siswa.',
        'kategori' => 'Event Seru',
        'tahun' => 2026,
        'status' => 'approved',
        'is_visible' => true,
    ]);

    GaleriPhoto::create([
        'galeri_id' => $galeri->id,
        'file_path' => 'galeri/pameran.jpg',
        'tipe' => 'file',
        'is_cover' => true,
    ]);

    $response = $this->get(route('frontend.galeri'));
    $response->assertStatus(200)
        ->assertSee('Pameran Sekolah');
});
