<?php

use App\Models\SecuritySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    SecuritySetting::query()->delete();

    $permission = Permission::firstOrCreate(['name' => 'access-admin-dashboard', 'guard_name' => 'web']);
    $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $role->givePermissionTo($permission);

    $this->admin = User::factory()->create();
    $this->admin->assignRole($role);
});

test('security settings page shows google drive oauth2 tab', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.security.index'));
    $response->assertStatus(200);
    $response->assertSee('tab-google-drive', false);
    $response->assertSee('Google Drive Backup', false);
});

test('security index passes oauth2 status to view when not configured', function () {
    Config::set('services.google_drive_oauth2.client_id', null);
    Config::set('services.google_drive_oauth2.client_secret', null);

    $response = $this->actingAs($this->admin)->get(route('admin.security.index'));
    $response->assertStatus(200);
    $response->assertViewHas('hasOAuth2Credentials', false);
    $response->assertViewHas('hasOAuth2EnvCredentials', false);
});

test('security index detects oauth2 env credentials configured', function () {
    Config::set('services.google_drive_oauth2.client_id', 'test-client-id.apps.googleusercontent.com');
    Config::set('services.google_drive_oauth2.client_secret', 'test-secret');

    $response = $this->actingAs($this->admin)->get(route('admin.security.index'));
    $response->assertStatus(200);
    $response->assertViewHas('hasOAuth2EnvCredentials', true);
    $response->assertViewHas('hasOAuth2Credentials', false);
});

test('security index detects existing oauth2 token correctly', function () {
    Config::set('services.google_drive_oauth2.client_id', 'test-client-id.apps.googleusercontent.com');
    Config::set('services.google_drive_oauth2.client_secret', 'test-secret');

    SecuritySetting::setValue('security_credentials', [
        'google_oauth2_credentials' => Crypt::encryptString(json_encode([
            'refresh_token' => 'valid-refresh-token',
            'access_token' => '',
        ])),
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.security.index'));
    $response->assertStatus(200);
    $response->assertViewHas('hasOAuth2Credentials', true);
    $response->assertViewHas('hasOAuth2EnvCredentials', true);
});

test('authorize endpoint redirects to google when env credentials are set', function () {
    Config::set('services.google_drive_oauth2.client_id', 'test-client-id.apps.googleusercontent.com');
    Config::set('services.google_drive_oauth2.client_secret', 'test-secret');

    $response = $this->actingAs($this->admin)->post(route('admin.security.google-drive.authorize'));

    // Should redirect to Google (external URL)
    $response->assertRedirect();
    $location = $response->headers->get('Location');
    expect($location)->toContain('accounts.google.com');
});

test('authorize endpoint fails when env credentials are missing', function () {
    Config::set('services.google_drive_oauth2.client_id', null);
    Config::set('services.google_drive_oauth2.client_secret', null);

    $response = $this->actingAs($this->admin)->post(route('admin.security.google-drive.authorize'));
    $response->assertSessionHasErrors(['google_oauth2']);
});

test('oauth2 callback handles missing code gracefully', function () {
    $response = $this->get(route('admin.security.google-drive.callback'));
    $response->assertRedirect(route('admin.security.index'));
    $response->assertSessionHasErrors('google_oauth2');
});

test('oauth2 callback handles user denied error from google', function () {
    $response = $this->get(route('admin.security.google-drive.callback', [
        'error' => 'access_denied',
    ]));
    $response->assertRedirect(route('admin.security.index'));
    $response->assertSessionHasErrors('google_oauth2');
});

test('oauth2 callback fails when state token is missing or invalid', function () {
    $response = $this->withSession([])->get(route('admin.security.google-drive.callback', [
        'code' => 'some-auth-code',
        'state' => 'wrong-state',
    ]));
    $response->assertRedirect(route('admin.security.index'));
    $response->assertSessionHasErrors('google_oauth2');
});

test('oauth2 callback is accessible without auth middleware', function () {
    // Callback should be reachable without being logged in (Google redirects here)
    $response = $this->get(route('admin.security.google-drive.callback', [
        'error' => 'access_denied',
    ]));

    // Should redirect to security index, not to the login page
    $response->assertRedirect(route('admin.security.index'));
});

test('revoke removes oauth2 token from storage', function () {
    Config::set('services.google_drive_oauth2.client_id', 'test-client-id');
    Config::set('services.google_drive_oauth2.client_secret', 'test-secret');

    SecuritySetting::setValue('security_credentials', [
        'google_oauth2_credentials' => Crypt::encryptString(json_encode([
            'refresh_token' => 'some-refresh-token',
            'access_token' => '',
        ])),
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.security.google-drive.revoke'));
    $response->assertRedirect(route('admin.security.index'));
    $response->assertSessionHas('success');

    $credentials = SecuritySetting::getValue('security_credentials', []);
    expect($credentials)->not->toHaveKey('google_oauth2_credentials');
});

test('backup page detects google credentials from oauth2 token', function () {
    Config::set('services.google_drive_oauth2.client_id', 'test-client-id');
    Config::set('services.google_drive_oauth2.client_secret', 'test-secret');

    SecuritySetting::setValue('security_credentials', [
        'google_oauth2_credentials' => Crypt::encryptString(json_encode([
            'refresh_token' => 'valid-refresh-token',
            'access_token' => '',
        ])),
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.backup.index'));
    $response->assertStatus(200);
    $response->assertViewHas('hasGoogleCredentials', true);
});

test('backup page shows no credentials when neither service account nor oauth2 configured', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.backup.index'));
    $response->assertStatus(200);
    $response->assertViewHas('hasGoogleCredentials', false);
});
