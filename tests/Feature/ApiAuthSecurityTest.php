<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('api user can login and receive token', function () {
    $user = User::factory()->withPassword('password123')->create();

    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
        'device_name' => 'test-device',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => ['id', 'name', 'email', 'roles']
        ]);
});

test('api login is rate limited', function () {
    $email = 'api-test@example.com';

    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/auth/login', [
            'email' => $email,
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ]);
    }

    $response = $this->postJson('/api/auth/login', [
        'email' => $email,
        'password' => 'wrong-password',
        'device_name' => 'test-device',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email');
    
    $errorMessage = $response->json('errors.email.0');
    $this->assertTrue(
        str_contains($errorMessage, 'Terlalu banyak percobaan') || 
        str_contains($errorMessage, 'Too many attempts')
    );
});

test('inactive api user cannot login', function () {
    $user = User::factory()->inactive()->withPassword('password123')->create();

    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
        'device_name' => 'test-device',
    ]);

    $response->assertStatus(403)
        ->assertJson(['message' => 'Akun Anda telah dinonaktifkan.']);
});

test('api token abilities are resolved server-side and ignore client input', function () {
    $user = User::factory()->withPassword('password123')->create();
    // Assuming the user has no roles, they should get ['read']
    
    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
        'device_name' => 'test-device',
        'abilities' => ['*'], // Client trying to escalate to super-admin
    ]);

    $response->assertStatus(200);
    
    $token = $user->tokens()->first();
    $this->assertEquals(['read'], $token->abilities);
    $this->assertNotEquals(['*'], $token->abilities);
});

test('api user can logout and revoke current token', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Token berhasil dicabut.']);
    
    $this->assertEmpty($user->tokens);
});

test('api user can logout from all devices', function () {
    $user = User::factory()->create();
    $user->createToken('device1');
    $user->createToken('device2');
    
    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/auth/logout-all');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Semua token berhasil dicabut.']);
    
    $this->assertEquals(0, $user->tokens()->count());
});
