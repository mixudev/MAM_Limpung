<?php

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
});

test('user can view profile edit page', function () {
    $user = User::factory()->create();
    $user->assignRole('siswa');

    $response = $this->actingAs($user)->get(route('user.profile.edit'));
    $response->assertStatus(200);
    $response->assertViewIs('dashboard.profile.edit');
});

test('user can update profile details without changing avatar', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);
    $user->assignRole('siswa');

    $response = $this->actingAs($user)->put(route('user.profile.update'), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('updated@example.com');
});

test('user can upload avatar image', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('siswa');

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($user)->put(route('user.profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->avatar)->not->toBeNull();
    
    // Assert the file was stored...
    Storage::disk('public')->assertExists($user->avatar);
});

test('old avatar is deleted when new avatar is uploaded', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar' => 'avatars/old-avatar.jpg',
    ]);
    $user->assignRole('siswa');

    // Create fake old avatar file
    Storage::disk('public')->put('avatars/old-avatar.jpg', 'fake content');
    Storage::disk('public')->assertExists('avatars/old-avatar.jpg');

    $newFile = UploadedFile::fake()->image('new-avatar.png');

    $response = $this->actingAs($user)->put(route('user.profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $newFile,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    
    // Assert old file deleted
    Storage::disk('public')->assertMissing('avatars/old-avatar.jpg');
    // Assert new file exists
    Storage::disk('public')->assertExists($user->avatar);
});

test('avatar validation rules are enforced', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->assignRole('siswa');

    // Non-image file upload
    $invalidFile = UploadedFile::fake()->create('document.pdf', 500);

    $response = $this->actingAs($user)->put(route('user.profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $invalidFile,
    ]);

    $response->assertSessionHasErrors('avatar');
});
