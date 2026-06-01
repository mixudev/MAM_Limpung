<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('halaman lupa password dapat diakses', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('permintaan reset password dengan email tidak terdaftar memberikan respon sukses generik', function () {
    $response = $this->post('/forgot-password', [
        'email' => 'unregistered@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'unregistered@example.com',
    ]);
});

test('permintaan reset password dengan email terdaftar menghasilkan token dan mengirim email', function () {
    $user = User::factory()->create(['email' => 'registered@example.com']);

    $response = $this->post('/forgot-password', [
        'email' => 'registered@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'registered@example.com',
    ]);
});

test('halaman atur ulang password dapat ditampilkan dengan token yang valid', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => 'user@example.com',
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $response = $this->get("/reset-password/{$token}?email=user@example.com");

    $response->assertStatus(200);
    $response->assertViewIs('auth.reset-password');
});

test('halaman atur ulang password tidak dapat ditampilkan jika token tidak cocok', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => 'user@example.com',
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $response = $this->get("/reset-password/wrong-token?email=user@example.com");

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error');
});

test('halaman atur ulang password tidak dapat ditampilkan jika token kedaluwarsa', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => 'user@example.com',
        'token' => Hash::make($token),
        'created_at' => now()->subMinutes(61),
    ]);

    $response = $this->get("/reset-password/{$token}?email=user@example.com");

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error');
});

test('proses atur ulang password berhasil dengan input valid', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('old-password'),
    ]);
    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => 'user@example.com',
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => 'user@example.com',
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'user@example.com',
    ]);

    $user->refresh();
    $this->assertTrue(Hash::check('new-password-123', $user->password));
});
