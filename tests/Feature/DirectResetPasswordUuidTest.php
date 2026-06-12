<?php

use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('admin can generate direct reset password link using uuid', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('siswa');

    $response = $this->actingAs($admin)
        ->post(route('admin.users.reset-password-link', $user));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Check session variable for URL contains the user uuid and NOT the ID
    $resetUrl = session('reset_url');
    expect($resetUrl)->not->toBeNull();
    expect($resetUrl)->toContain('/reset-password-direct/'.$user->uuid);
    expect($resetUrl)->not->toContain('/reset-password-direct/'.$user->id.'/');
});

test('user can view direct reset password form with valid uuid signed URL', function () {
    $user = User::factory()->create();
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $resetUrl = URL::temporarySignedRoute(
        'password.reset.direct',
        now()->addHours(2),
        ['uuid' => $user->uuid, 'token' => $token]
    );

    $response = $this->get($resetUrl);

    $response->assertStatus(200);
    $response->assertViewIs('auth.reset-password-direct');
    $response->assertSee($user->email);
});

test('user can update password using direct reset password signed URL', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password-123'),
    ]);
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $resetUrl = URL::temporarySignedRoute(
        'password.reset.direct',
        now()->addHours(2),
        ['uuid' => $user->uuid, 'token' => $token]
    );

    $response = $this->post($resetUrl, [
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $user->refresh();
    $this->assertTrue(Hash::check('new-password-123', $user->password));

    // Check if token was deleted
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

test('direct reset password fails with unsigned URL or invalid uuid', function () {
    $user = User::factory()->create();
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    // Unsigned URL
    $unsignedUrl = route('password.reset.direct', ['uuid' => $user->uuid, 'token' => $token]);
    $response = $this->get($unsignedUrl);
    $response->assertStatus(403);

    // Invalid UUID signed URL
    $invalidResetUrl = URL::temporarySignedRoute(
        'password.reset.direct',
        now()->addHours(2),
        ['uuid' => (string) Str::uuid(), 'token' => $token]
    );
    $response = $this->get($invalidResetUrl);
    $response->assertStatus(404);
});
