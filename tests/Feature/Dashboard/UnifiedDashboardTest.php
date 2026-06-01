<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed permissions and roles
    $this->seed();
});

test('unauthenticated user cannot access dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('user without dashboard permission cannot access dashboard', function () {
    $user = User::factory()->create();
    $user->removeRole('admin');
    $user->removeRole('guru');
    $user->removeRole('siswa');
    $user->removeRole('super-admin');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertForbidden();
});

test('super admin can access unified dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $user->givePermissionTo('access-super-admin-dashboard');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.index')
        ->assertViewHas(['user', 'roles', 'permissions', 'stats', 'accessibleFeatures']);
});

test('admin can access unified dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $user->givePermissionTo('access-admin-dashboard');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.index')
        ->assertViewHas(['user', 'roles', 'permissions', 'stats', 'accessibleFeatures']);
});

test('guru can access unified dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('guru');
    $user->givePermissionTo('access-guru-dashboard');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.index')
        ->assertViewHas(['user', 'roles', 'permissions', 'stats', 'accessibleFeatures']);
});

test('siswa can access unified dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('siswa');
    $user->givePermissionTo('access-siswa-dashboard');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.index')
        ->assertViewHas(['user', 'roles', 'permissions', 'stats', 'accessibleFeatures']);
});

test('inactive user cannot access dashboard', function () {
    $user = User::factory()->create(['is_active' => false]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('super admin receives ppdb stats', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $user->givePermissionTo('access-super-admin-dashboard');

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful();
    $stats = $response->viewData('stats');

    expect($stats)->toHaveKey('total_ppdb');
    expect($stats)->toHaveKey('ppdb_pending');
    expect($stats)->toHaveKey('ppdb_diterima');
    expect($stats)->toHaveKey('ppdb_ditolak');
});

test('admin receives accessible features', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $user->givePermissionTo('access-admin-dashboard');
    $user->givePermissionTo('access-admin-dashboard');

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertSuccessful();
    $features = $response->viewData('accessibleFeatures');

    expect($features)->not->toBeEmpty();
    $featureNames = array_column($features, 'name');
    expect($featureNames)->toContain('Article Management');
});
