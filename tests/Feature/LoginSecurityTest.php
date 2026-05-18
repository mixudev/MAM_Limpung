<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user can login with correct credentials', function () {
    $user = User::factory()->withPassword('password123')->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect(route($user->dashboardRoute()));
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with wrong credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login is rate limited by ip and email', function () {
    $email = 'test@example.com';

    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'email' => $email,
            'password' => 'wrong-password',
        ]);
    }

    $response = $this->post('/login', [
        'email' => $email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    // Check for either Indonesian or English throttle message
    $errorMessage = session('errors')->get('email')[0];
    $this->assertTrue(
        str_contains($errorMessage, 'Terlalu banyak percobaan') || 
        str_contains($errorMessage, 'Too many login attempts')
    );
});

test('login has global rate limiting for email', function () {
    $email = 'global@example.com';

    for ($i = 0; $i < 15; $i++) {
        $this->withServerVariables(['REMOTE_ADDR' => "192.168.1.$i"])
            ->post('/login', [
                'email' => $email,
                'password' => 'wrong-password',
            ]);
    }

    $response = $this->post('/login', [
        'email' => $email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $errorMessage = session('errors')->get('email')[0];
    $this->assertTrue(
        str_contains($errorMessage, 'Terlalu banyak percobaan') || 
        str_contains($errorMessage, 'Too many login attempts')
    );
});

test('inactive user cannot login', function () {
    $user = User::factory()->inactive()->withPassword('password123')->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertTrue(Str::contains(session('errors')->get('email')[0], 'Akun Anda telah dinonaktifkan'));
    $this->assertGuest();
});

test('remember me is ignored and session expires on close', function () {
    $user = User::factory()->withPassword('password123')->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
        'remember' => '1', // Attempting to use remember
    ]);

    $response->assertRedirect();
    
    // Check that the remember_web cookie is NOT present
    $response->assertCookieMissing(Auth::guard()->getRecallerName());
});

test('session is regenerated after login to prevent session fixation', function () {
    $user = User::factory()->withPassword('password123')->create();
    
    $oldSessionId = session()->getId();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertNotEquals($oldSessionId, session()->getId());
});
