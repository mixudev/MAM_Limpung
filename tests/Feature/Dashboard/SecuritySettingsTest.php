<?php

use App\Models\PpdbSetting;
use App\Models\SecuritySetting;
use App\Models\User;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Clear settings before each test
    PpdbSetting::whereIn('key', ['security_credentials', 'backup_settings', 'backup_history'])->delete();
    SecuritySetting::query()->delete();

    // Create admin user and permissions
    $this->permission = Permission::firstOrCreate(['name' => 'access-admin-dashboard', 'guard_name' => 'web']);
    $this->role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $this->role->givePermissionTo($this->permission);

    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);

    $this->user = User::factory()->create();
});

test('unauthorized users cannot access security settings page', function () {
    $response = $this->actingAs($this->user)->get(route('admin.security.index'));
    $response->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can access security settings page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.security.index'));
    $response->assertStatus(200);
    $response->assertSee('Pusat Keamanan &amp; Sistem Backup');
});

test('admin can save google service account credentials securely', function () {
    $jsonCredentials = json_encode([
        'type' => 'service_account',
        'project_id' => 'mam-limpung-project',
        'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0...',
        'client_email' => 'backup-sync@mam-limpung-project.iam.gserviceaccount.com',
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.security.credentials.update'), [
        'google_service_account_json' => $jsonCredentials,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Verify stored securely in SecuritySetting and matches when decrypted
    $credentials = SecuritySetting::getValue('security_credentials');
    expect($credentials['google_service_account_json'])->not->toBe($jsonCredentials);

    $decrypted = Crypt::decryptString($credentials['google_service_account_json']);
    expect($decrypted)->toBe($jsonCredentials);
});

test('admin can save backup settings with encryption disabled', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.security.backup.settings'), [
        'schedule' => 'weekly',
        'cron_expression' => '0 0 * * 0',
        'enabled' => '1',
        'backup_db' => '1',
        'backup_storage' => '1',
        'google_drive_enabled' => '0',
        'google_drive_folder_id' => '1234567890abcdef',
        'retention_days' => '45',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $settings = SecuritySetting::getValue('backup_settings');
    expect($settings['enabled'])->toBeTrue();
    expect($settings['schedule'])->toBe('weekly');
    expect($settings['retention_days'])->toBe(45);
    expect($settings['google_drive_folder_id'])->toBe('1234567890abcdef');
    expect($settings['encryption_enabled'])->toBeFalse();
});

test('admin cannot enable encryption without generating key first', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.security.backup.settings'), [
        'schedule' => 'daily',
        'enabled' => '1',
        'encryption_enabled' => '1',
        'retention_days' => '30',
    ]);

    $response->assertSessionHasErrors(['error']);
});

test('admin can generate and download encryption key', function () {
    // Generate key
    $generateResponse = $this->actingAs($this->admin)->post(route('admin.security.backup.generate-key'));
    $generateResponse->assertRedirect();
    $generateResponse->assertSessionHas('success');

    $settings = SecuritySetting::getValue('backup_settings');
    expect($settings['passphrase'])->not->toBeEmpty();

    $decryptedKey = Crypt::decryptString($settings['passphrase']);
    expect(strlen($decryptedKey))->toBe(64); // 64-char hex key

    // Download key
    $downloadResponse = $this->actingAs($this->admin)->get(route('admin.security.backup.download-key'));
    $downloadResponse->assertStatus(200);
    $downloadResponse->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($downloadResponse->streamedContent())->toContain($decryptedKey);
});

test('admin can run backup manually and verify decryption successfully', function () {
    // Setup encryption key
    $rawKey = bin2hex(random_bytes(32));
    $encryptedKey = Crypt::encryptString($rawKey);
    
    SecuritySetting::setValue('backup_settings', [
        'enabled' => true,
        'schedule' => 'daily',
        'backup_db' => true,
        'backup_storage' => false,
        'encryption_enabled' => true,
        'passphrase' => $encryptedKey,
        'google_drive_enabled' => false,
        'retention_days' => 30,
    ]);

    // Ensure backups folder exists
    $backupsPath = storage_path('app/backups');
    if (!file_exists($backupsPath)) {
        mkdir($backupsPath, 0755, true);
    }

    // Trigger manual backup via AJAX
    $response = $this->actingAs($this->admin)->postJson(route('admin.security.backup.run'));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect($data['log']['encrypted'])->toBeTrue();
    expect($data['log']['status'])->toBe('success');

    $filename = $data['log']['filename'];
    $filePath = storage_path('app/backups/' . $filename);
    expect(file_exists($filePath))->toBeTrue();

    // Verify correct decryption via verify endpoint
    $verifyResponse = $this->actingAs($this->admin)->postJson(route('admin.security.backup.verify'), [
        'filename' => $filename,
        'passphrase' => $rawKey,
    ]);

    $verifyResponse->assertStatus(200);
    $verifyData = $verifyResponse->json();
    expect($verifyData['success'])->toBeTrue();
    expect($verifyData['report']['has_db_dump'])->toBeTrue();

    // Verify decryption failure with wrong password
    $verifyFailResponse = $this->actingAs($this->admin)->postJson(route('admin.security.backup.verify'), [
        'filename' => $filename,
        'passphrase' => 'wrongpassphrase12345678901234567890123456789012345678901234567890123456',
    ]);

    $verifyFailResponse->assertStatus(400);
    expect($verifyFailResponse->json()['success'])->toBeFalse();

    // Clean up backup file
    if (file_exists($filePath)) {
        unlink($filePath);
    }
});

