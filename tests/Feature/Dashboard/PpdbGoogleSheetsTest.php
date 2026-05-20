<?php

use App\Models\PpdbSetting;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Clear Google Sheets settings before each test
    PpdbSetting::where('key', 'google_sheets')->delete();

    // Create admin user and permissions if they don't exist
    $this->permission = Permission::firstOrCreate(['name' => 'access-admin-dashboard', 'guard_name' => 'web']);
    $this->role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $this->role->givePermissionTo($this->permission);

    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);

    $this->user = User::factory()->create();
});

test('unauthorized users cannot access google sheets settings page', function () {
    $response = $this->actingAs($this->user)->get(route('admin.ppdb.google-sheets.edit'));
    $response->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can access google sheets settings page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.ppdb.google-sheets.edit'));
    $response->assertStatus(200);
    $response->assertSee('Integrasi Google Sheets');
});

test('admin can save google sheets settings with encrypted credentials', function () {
    $jsonCredentials = json_encode([
        'type' => 'service_account',
        'project_id' => 'test-project',
        'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQ...',
        'client_email' => 'test-service-account@test-project.iam.gserviceaccount.com',
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.ppdb.google-sheets.update'), [
        'spreadsheet_id' => 'test_spreadsheet_123',
        'is_enabled' => '1',
        'service_account_json' => $jsonCredentials,
        'header_style' => 'purple',
        'sync_fields' => ['no_registrasi', 'nama_lengkap', 'nisn'],
    ]);

    $response->assertRedirect(route('admin.ppdb.google-sheets.edit'));
    $response->assertSessionHas('success');

    // Verify stored configuration is secure & matches
    $settings = PpdbSetting::getValue('google_sheets');
    expect($settings['spreadsheet_id'])->toBe('test_spreadsheet_123');
    expect($settings['is_enabled'])->toBeTrue();

    // Credentials must be stored ENCRYPTED in database
    expect($settings['service_account_json'])->not->toBe($jsonCredentials);

    // Decrypting must return the exact original JSON
    $decrypted = Crypt::decryptString($settings['service_account_json']);
    expect($decrypted)->toBe($jsonCredentials);
});

test('test connection returns connection failed status when settings are unconfigured', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.ppdb.google-sheets.test'));

    $response->assertStatus(200);
    $data = $response->json();
    expect($data['success'])->toBeFalse();
    expect($data['message'])->toContain('Konfigurasi Google Sheets kosong');
});
