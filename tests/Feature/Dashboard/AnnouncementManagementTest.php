<?php

use App\Models\AnnounceAd;
use App\Models\AnnounceAlert;
use App\Models\AnnounceText;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
    Storage::fake('public');
});

test('unauthenticated guest cannot access announcements index or sub-resource actions', function () {
    $this->get(route('admin.announcements.index'))->assertRedirect(route('login'));
    $this->get(route('admin.announcements.texts.create'))->assertRedirect(route('login'));
    $this->post(route('admin.announcements.texts.store'))->assertRedirect(route('login'));
});

test('unauthorized user cannot access announcements index or sub-resource actions', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $this->actingAs($siswa)->get(route('admin.announcements.index'))->assertStatus(302)->assertRedirect(route('frontend.home'));
    $this->actingAs($siswa)->get(route('admin.announcements.texts.create'))->assertStatus(302)->assertRedirect(route('frontend.home'));
    $this->actingAs($siswa)->post(route('admin.announcements.texts.store'), [])->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can load announcements index aggregator page with three sections', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.announcements.index'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.announcement.index')
        ->assertSee('SECTION 01')
        ->assertSee('SECTION 02')
        ->assertSee('SECTION 03');
});

// --- AnnounceText & Settings tests ---
test('authorized admin can create running text announcement', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.announcements.texts.create'));
    $response->assertStatus(200)->assertViewIs('dashboard.admin.announcement.text.create');

    $data = [
        'title' => 'Pengumuman Running Baru',
        'content' => 'Ini teks pengumuman yang sedang berjalan di beranda sekolah.',
        'is_active' => '1',
    ];

    $postResponse = $this->actingAs($admin)->post(route('admin.announcements.texts.store'), $data);
    $postResponse->assertRedirect(route('admin.announcements.index'));

    $this->assertDatabaseHas('announce_texts', [
        'title' => 'Pengumuman Running Baru',
        'is_active' => true,
    ]);
});

test('authorized admin can edit and update running text', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $text = AnnounceText::create([
        'title' => 'Judul Awal',
        'content' => 'Isi awal',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.announcements.texts.edit', $text));
    $response->assertStatus(200)->assertViewIs('dashboard.admin.announcement.text.edit');

    $updateData = [
        'title' => 'Judul Baru',
        'content' => 'Isi baru',
        'is_active' => '1',
    ];

    $putResponse = $this->actingAs($admin)->put(route('admin.announcements.texts.update', $text), $updateData);
    $putResponse->assertRedirect(route('admin.announcements.index'));

    $this->assertDatabaseHas('announce_texts', [
        'id' => $text->id,
        'title' => 'Judul Baru',
    ]);
});

test('authorized admin can toggle and delete running text', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $text = AnnounceText::create([
        'title' => 'Teks Keaktifan',
        'content' => 'Isi teks keaktifan',
        'is_active' => true,
    ]);

    // Toggle active
    $toggleResponse = $this->actingAs($admin)->post(route('admin.announcements.texts.toggle-active', $text));
    $toggleResponse->assertRedirect(route('admin.announcements.index'));
    expect($text->fresh()->is_active)->toBeFalse();

    // Delete
    $deleteResponse = $this->actingAs($admin)->delete(route('admin.announcements.texts.destroy', $text));
    $deleteResponse->assertRedirect(route('admin.announcements.index'));
    $this->assertSoftDeleted('announce_texts', ['id' => $text->id]);
});

// --- AnnounceAlert tests ---
test('authorized admin can create popup alert with multiple images upload', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $image1 = UploadedFile::fake()->create('popup_banner1.jpg', 150, 'image/jpeg');
    $image2 = UploadedFile::fake()->create('popup_banner2.jpg', 150, 'image/jpeg');

    $data = [
        'title' => 'Popup Promosi PPDB',
        'content' => 'Gelombang ke-2 resmi dibuka bagi calon siswa baru!',
        'images' => [$image1, $image2],
        'action_url' => 'https://example.com/ppdb',
        'action_text' => 'Daftar',
        'popup_size' => 'md',
        'display_frequency' => 'once_per_session',
        'target_page' => 'frontend.ppdb.index',
        'is_active' => '1',
    ];

    $response = $this->actingAs($admin)->post(route('admin.announcements.alerts.store'), $data);
    $response->assertRedirect(route('admin.announcements.index'));

    $alert = AnnounceAlert::where('title', 'Popup Promosi PPDB')->first();
    expect($alert)->not->toBeNull();
    expect($alert->image)->toBeArray();
    expect(count($alert->image))->toBe(2);

    Storage::disk('public')->assertExists($alert->image[0]);
    Storage::disk('public')->assertExists($alert->image[1]);
});

test('authorized admin can update popup alert selectively retaining some images', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $alert = AnnounceAlert::create([
        'title' => 'Popup Awal',
        'image' => ['path1.jpg', 'path2.jpg'],
        'popup_size' => 'md',
        'display_frequency' => 'once_per_session',
        'target_page' => 'home_only',
        'is_active' => true,
    ]);

    Storage::disk('public')->put('path1.jpg', 'fake content');
    Storage::disk('public')->put('path2.jpg', 'fake content');

    $newImage = UploadedFile::fake()->create('popup_new.jpg', 150, 'image/jpeg');

    $data = [
        'title' => 'Popup Diperbarui',
        'retained_images' => ['path1.jpg'],
        'images' => [$newImage],
        'popup_size' => 'lg',
        'display_frequency' => 'every_load',
        'target_page' => 'all_pages',
        'is_active' => '1',
    ];

    $response = $this->actingAs($admin)->put(route('admin.announcements.alerts.update', $alert), $data);
    $response->assertRedirect(route('admin.announcements.index'));

    $alert->refresh();
    expect($alert->title)->toBe('Popup Diperbarui');
    expect($alert->image)->toBeArray();
    expect(count($alert->image))->toBe(2);
    expect($alert->image[0])->toBe('path1.jpg');

    Storage::disk('public')->assertExists('path1.jpg');
    Storage::disk('public')->assertMissing('path2.jpg');
    Storage::disk('public')->assertExists($alert->image[1]);
});

// --- AnnounceAd tests ---
test('authorized admin can create banner ad', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $image = UploadedFile::fake()->create('ad_banner.png', 100, 'image/png');

    $data = [
        'title' => 'Iklan Ekstrakurikuler Pilihan',
        'description' => 'Ini deskripsi iklan ekskul pilihan yang seru.',
        'image' => $image,
        'action_url' => 'https://example.com/ekskul',
        'action_text' => 'Info Ekskul',
        'is_active' => '1',
    ];

    $response = $this->actingAs($admin)->post(route('admin.announcements.ads.store'), $data);
    $response->assertRedirect(route('admin.announcements.index'));

    $ad = AnnounceAd::where('title', 'Iklan Ekstrakurikuler Pilihan')->first();
    expect($ad)->not->toBeNull();
    expect($ad->description)->toBe('Ini deskripsi iklan ekskul pilihan yang seru.');
    expect($ad->image)->not->toBeNull();
    Storage::disk('public')->assertExists($ad->image);
});

// --- Cache Invalidation tests ---
test('cache is automatically invalidated when any model changes', function () {
    Cache::shouldReceive('forget')
        ->times(3)
        ->with('active_announcements_popups')
        ->andReturn(true);

    // Save triggers forget
    $text = AnnounceText::create([
        'title' => 'Teks Cache',
        'content' => 'Konten teks cache',
        'is_active' => true,
    ]);

    // Update triggers forget
    $text->update(['title' => 'Teks Cache Baru']);

    // Delete triggers forget
    $text->delete();
});
