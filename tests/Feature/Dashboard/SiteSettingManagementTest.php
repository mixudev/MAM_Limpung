<?php

use App\Models\SiteSetting;
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

test('unauthenticated guest cannot access site settings page or update action', function () {
    $this->get(route('admin.settings.edit'))->assertRedirect(route('login'));
    $this->put(route('admin.settings.update'), [])->assertRedirect(route('login'));
});

test('unauthorized user cannot access site settings page or update action', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');
    $this->actingAs($siswa)->get(route('admin.settings.edit'))->assertStatus(302)->assertRedirect(route('frontend.home'));
    $this->actingAs($siswa)->put(route('admin.settings.update'), [])->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can view settings edit page with default seed data', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.settings.edit'));
    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.settings.edit')
        ->assertSee('MAM Limpung')
        ->assertSee('info@mamlimpung.sch.id');
});

test('authorized admin can update site settings along with logo upload', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $logo = UploadedFile::fake()->create('school_logo.png', 100, 'image/png');

    $data = [
        'school_name' => 'MAM Limpung Update',
        'logo' => $logo,
        'about_short' => 'Sekolah Islam berprestasi tinggi.',
        'email' => 'contact@mamlimpung.sch.id',
        'phone' => '+62 888 8888 8888',
        'whatsapp' => '6288888888888',
        'address' => 'Jalan Baru Limpung Batang',
        'facebook_url' => 'https://facebook.com/mamlimpung',
        'instagram_url' => 'https://instagram.com/mamlimpung',
        'youtube_url' => 'https://youtube.com/mamlimpung',
        'twitter_url' => 'https://twitter.com/mamlimpung',
        'meta_title' => 'MAM Limpung - Web Resmi',
        'meta_description' => 'Website resmi MA Muhammadiyah Limpung',
    ];

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), $data);
    $response->assertRedirect(route('admin.settings.edit'));

    $setting = SiteSetting::first();
    expect($setting->school_name)->toBe('MAM Limpung Update');
    expect($setting->logo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($setting->logo_path);

    $this->assertDatabaseHas('site_settings', [
        'school_name' => 'MAM Limpung Update',
        'email' => 'contact@mamlimpung.sch.id',
    ]);
});

test('cache is automatically invalidated when site settings are updated', function () {
    Cache::shouldReceive('forget')
        ->twice()
        ->with('site_settings')
        ->andReturn(true);

    $setting = SiteSetting::create([
        'school_name' => 'Original School',
    ]);

    $setting->update(['school_name' => 'Updated School']);
});
