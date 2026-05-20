<?php

use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('unauthenticated guest cannot access roles and permissions management', function () {
    $this->get(route('super-admin.roles-permissions.index'))->assertRedirect(route('login'));
    $this->post(route('super-admin.roles.store'), [])->assertRedirect(route('login'));
});

test('unauthorized users (siswa/guru/admin) cannot access roles and permissions management', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($siswa)->get(route('super-admin.roles-permissions.index'))->assertStatus(302)->assertRedirect(route('frontend.home'));
    $this->actingAs($admin)->get(route('super-admin.roles-permissions.index'))->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized super admin can access roles and permissions index page', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $response = $this->actingAs($superAdmin)->get(route('super-admin.roles-permissions.index'));
    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.security.index')
        ->assertSee('Kelola Roles')
        ->assertSee('super-admin');
});

test('super admin can create a new role and assign permissions', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $data = [
        'name' => 'staf-keuangan',
        'display_name' => 'Staf Keuangan',
        'description' => 'Mengelola arus keuangan sekolah.',
        'level' => 30,
        'permissions' => ['view-reports', 'export-reports'],
    ];

    $response = $this->actingAs($superAdmin)->post(route('super-admin.roles.store'), $data);
    $response->assertRedirect(route('super-admin.roles-permissions.index'));

    $role = Role::findByName('staf-keuangan', 'web');
    expect($role->display_name)->toBe('Staf Keuangan');
    expect($role->level)->toBe(30);
    expect($role->hasPermissionTo('view-reports'))->toBeTrue();
});

test('super admin cannot edit name or level of system roles', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $role = Role::findByName('admin', 'web');

    $data = [
        'name' => 'admin-modified',
        'display_name' => 'Administrator Baru',
        'description' => 'System admin description.',
        'level' => 99, // original level is 50
        'permissions' => ['view-users'],
    ];

    $response = $this->actingAs($superAdmin)->put(route('super-admin.roles.update', $role), $data);
    $response->assertRedirect(route('super-admin.roles-permissions.index'));

    $role->refresh();
    // name and level must not change for system roles
    expect($role->name)->toBe('admin');
    expect($role->level)->toBe(50);
    expect($role->display_name)->toBe('Administrator Baru');
});

test('super admin cannot delete system roles', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $role = Role::findByName('guru', 'web');

    $response = $this->actingAs($superAdmin)->delete(route('super-admin.roles.destroy', $role));
    $response->assertRedirect(route('super-admin.roles-permissions.index'));

    expect(Role::where('name', 'guru')->exists())->toBeTrue();
});

test('super admin can delete custom roles', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $customRole = Role::create([
        'name' => 'temp-role',
        'display_name' => 'Temporary Role',
        'level' => 5,
        'guard_name' => 'web',
    ]);

    $response = $this->actingAs($superAdmin)->delete(route('super-admin.roles.destroy', $customRole));
    $response->assertRedirect(route('super-admin.roles-permissions.index'));

    expect(Role::where('name', 'temp-role')->exists())->toBeFalse();
});
