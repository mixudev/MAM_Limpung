<?php

use App\Models\PpdbSetting;
use App\Models\SecuritySetting;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Support\Facades\Crypt;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);

    PpdbSetting::where('key', 'google_sheets')->delete();
    SecuritySetting::query()->delete();

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

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

test('admin can save google sheets settings', function () {
    $jsonCredentials = json_encode([
        'type' => 'service_account',
        'project_id' => 'test-project',
        'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQ...',
        'client_email' => 'test-service-account@test-project.iam.gserviceaccount.com',
    ]);

    // Save service account centrally in security settings
    SecuritySetting::setValue('security_credentials', [
        'google_service_account_json' => Crypt::encryptString($jsonCredentials),
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.ppdb.google-sheets.update'), [
        'spreadsheet_id' => 'test_spreadsheet_123',
        'is_enabled' => '1',
        'header_style' => 'purple',
        'sync_fields' => ['no_registrasi', 'nama_lengkap', 'nisn'],
    ]);

    $response->assertRedirect(route('admin.ppdb.google-sheets.edit'));
    $response->assertSessionHas('success');

    // Verify stored configuration is secure & matches
    $settings = PpdbSetting::getValue('google_sheets');
    expect($settings['spreadsheet_id'])->toBe('test_spreadsheet_123');
    expect($settings['is_enabled'])->toBeTrue();

    // Verify page shows configured credentials email
    $editResponse = $this->actingAs($this->admin)->get(route('admin.ppdb.google-sheets.edit'));
    $editResponse->assertSee('test-service-account@test-project.iam.gserviceaccount.com');
});

test('test connection returns connection failed status when settings are unconfigured', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.ppdb.google-sheets.test'));

    $response->assertStatus(200);
    $data = $response->json();
    expect($data['success'])->toBeFalse();
    expect($data['message'])->toContain('Google Sheets');
});
