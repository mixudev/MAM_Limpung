<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('halaman request OTP dapat diakses', function () {
    $response = $this->get('/login-otp');

    $response->assertStatus(200);
});

test('request OTP dengan email tidak terdaftar memberikan respon sukses generik dan tidak menyimpan OTP', function () {
    $response = $this->post('/login-otp', [
        'email' => 'unregistered@example.com',
    ]);

    $response->assertRedirect(route('login.otp.verify', ['email' => 'unregistered@example.com']));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('otp_codes', [
        'email' => 'unregistered@example.com',
    ]);
});

test('request OTP dengan email terdaftar menghasilkan OTP dan menyimpannya ke database', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);

    $response = $this->post('/login-otp', [
        'email' => 'user@example.com',
    ]);

    $response->assertRedirect(route('login.otp.verify', ['email' => 'user@example.com']));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('otp_codes', [
        'email' => 'user@example.com',
    ]);
});

test('akses halaman verifikasi tanpa email dialihkan ke request OTP', function () {
    $response = $this->get('/login-otp/verify');

    $response->assertRedirect(route('login.otp'));
    $response->assertSessionHas('error');
});

test('akses halaman verifikasi dengan email mengembalikan status 200', function () {
    $response = $this->get('/login-otp/verify?email=user@example.com');

    $response->assertStatus(200);
    $response->assertViewIs('auth.verify-otp');
});

test('verifikasi OTP yang salah meningkatkan hitungan attempts dan menyisakan percobaan', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    
    DB::table('otp_codes')->insert([
        'email' => 'user@example.com',
        'otp_code' => hash('sha256', '123456'),
        'attempts' => 0,
        'expires_at' => now()->addMinutes(5),
        'created_at' => now(),
    ]);

    $response = $this->post('/login-otp/verify', [
        'email' => 'user@example.com',
        'otp_code' => '000000', // Salah
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    
    $otpRecord = DB::table('otp_codes')->where('email', 'user@example.com')->first();
    $this->assertEquals(1, $otpRecord->attempts);
});

test('verifikasi OTP salah sebanyak 3 kali menghapus record OTP secara otomatis', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    
    DB::table('otp_codes')->insert([
        'email' => 'user@example.com',
        'otp_code' => hash('sha256', '123456'),
        'attempts' => 2, // 2 kali percobaan sebelumnya
        'expires_at' => now()->addMinutes(5),
        'created_at' => now(),
    ]);

    $response = $this->post('/login-otp/verify', [
        'email' => 'user@example.com',
        'otp_code' => '000000', // Percobaan ke-3 yang salah
    ]);

    $response->assertRedirect(route('login.otp'));
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('otp_codes', [
        'email' => 'user@example.com',
    ]);
});

test('verifikasi OTP yang kedaluwarsa dibatalkan', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    
    DB::table('otp_codes')->insert([
        'email' => 'user@example.com',
        'otp_code' => hash('sha256', '123456'),
        'attempts' => 0,
        'expires_at' => now()->subSecond(), // Kedaluwarsa
        'created_at' => now()->subMinutes(6),
    ]);

    $response = $this->post('/login-otp/verify', [
        'email' => 'user@example.com',
        'otp_code' => '123456',
    ]);

    $response->assertRedirect(route('login.otp'));
    $response->assertSessionHas('error');
});

test('verifikasi OTP yang benar melakukan login pengguna dan menghapus record', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    
    DB::table('otp_codes')->insert([
        'email' => 'user@example.com',
        'otp_code' => hash('sha256', '123456'),
        'attempts' => 0,
        'expires_at' => now()->addMinutes(5),
        'created_at' => now(),
    ]);

    $response = $this->post('/login-otp/verify', [
        'email' => 'user@example.com',
        'otp_code' => '123456', // Benar
    ]);

    $response->assertRedirect(route($user->dashboardRoute()));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('otp_codes', [
        'email' => 'user@example.com',
    ]);
    
    $this->assertAuthenticatedAs($user);
});
