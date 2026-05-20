<?php

use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('unauthenticated guest cannot access user management', function () {
    $this->get(route('super-admin.users.index'))->assertRedirect(route('login'));
    $this->post(route('super-admin.users.store'), [])->assertRedirect(route('login'));
});

test('unauthorized users (siswa / guru without view-users permission) cannot access user management', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $this->actingAs($siswa)->get(route('super-admin.users.index'))->assertStatus(302)->assertRedirect(route('frontend.home'));
    $this->actingAs($guru)->get(route('admin.users.index'))->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin or super admin can access user index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin'); // admin has view-users permission by default

    $response = $this->actingAs($admin)->get(route('admin.users.index'));
    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.security.users.index')
        ->assertSee('User Accounts')
        ->assertSee($admin->name);
});

test('authorized user can create a new user and assign roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $data = [
        'name' => 'Budi Santoso',
        'email' => 'budis@sekolah.sch.id',
        'password' => 'secret12345',
        'is_active' => '1',
        'roles' => ['guru'],
    ];

    $response = $this->actingAs($admin)->post(route('admin.users.store'), $data);
    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user = User::where('email', 'budis@sekolah.sch.id')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Budi Santoso');
    expect($user->is_active)->toBeTrue();
    expect($user->hasRole('guru'))->toBeTrue();
});

test('authorized user can update another user details and roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $otherUser = User::factory()->create(['is_active' => true]);
    $otherUser->assignRole('siswa');

    $data = [
        'name' => 'Siswa Updated Name',
        'email' => 'siswa.updated@sekolah.sch.id',
        'password' => '', // leave empty to not update
        'is_active' => '0', // deactivate
        'roles' => ['guru'],
    ];

    $response = $this->actingAs($admin)->put(route('admin.users.update', $otherUser), $data);
    $response->assertRedirect();
    $response->assertSessionHas('success');

    $otherUser->refresh();
    expect($otherUser->name)->toBe('Siswa Updated Name');
    expect($otherUser->email)->toBe('siswa.updated@sekolah.sch.id');
    expect($otherUser->is_active)->toBeFalse();
    expect($otherUser->hasRole('guru'))->toBeTrue();
    expect($otherUser->hasRole('siswa'))->toBeFalse();
});

test('user cannot deactivate themselves', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin');

    $data = [
        'name' => 'Admin Self Update',
        'email' => $admin->email,
        'password' => '',
        'is_active' => '0', // try to deactivate self
        'roles' => ['admin'],
    ];

    $response = $this->actingAs($admin)->put(route('admin.users.update', $admin), $data);
    $response->assertRedirect();
    $response->assertSessionHas('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');

    $admin->refresh();
    expect($admin->is_active)->toBeTrue();
});

test('user cannot delete themselves', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));
    $response->assertRedirect();
    $response->assertSessionHas('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

    expect(User::where('id', $admin->id)->exists())->toBeTrue();
});

test('authorized user can delete another user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $otherUser = User::factory()->create();
    $otherUser->assignRole('siswa');

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $otherUser));
    $response->assertRedirect();
    $response->assertSessionHas('success');

    // The user should be soft deleted
    expect(User::where('id', $otherUser->id)->exists())->toBeFalse();
    expect(User::withTrashed()->where('id', $otherUser->id)->exists())->toBeTrue();
});

test('authorized user can view user info/edit page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $otherUser = User::factory()->create();
    $otherUser->assignRole('siswa');

    $response = $this->actingAs($admin)->get(route('admin.users.show', $otherUser));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.security.users.show')
        ->assertSee('Form Edit Akun User')
        ->assertSee($otherUser->name)
        ->assertSee($otherUser->email);
});
