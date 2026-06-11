<?php

use App\Models\BackupLog;
use App\Models\SecuritySetting;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);

    SecuritySetting::query()->delete();
    BackupLog::query()->delete();

    // Routes backup & security require access-super-admin-dashboard permission
    $this->admin = User::factory()->create();
    $this->admin->assignRole('super-admin');

    $this->user = User::factory()->create();
});

test('unauthorized users cannot access security settings page', function () {
    // CheckPermission middleware throws 403 (not redirect) for authenticated users without permission
    $response = $this->actingAs($this->user)->get(route('admin.security.index'));
    $response->assertStatus(403);
});

test('authorized admin can access security settings page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.security.index'));
    $response->assertStatus(200);
    $response->assertSee('Pusat Keamanan & Kredensial', false);
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

// ── Backup route tests (now at admin.backup.*) ────────────────────────────────

test('authorized admin can access backup page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.backup.index'));
    $response->assertStatus(200);
    $response->assertSee('Manajemen Backup', false);
});

test('admin can save backup settings with encryption disabled', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.backup.settings'), [
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
    $response = $this->actingAs($this->admin)->post(route('admin.backup.settings'), [
        'schedule' => 'daily',
        'enabled' => '1',
        'encryption_enabled' => '1',
        'retention_days' => '30',
    ]);

    $response->assertSessionHasErrors(['error']);
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
    if (! file_exists($backupsPath)) {
        mkdir($backupsPath, 0755, true);
    }

    // Trigger manual backup via AJAX
    $response = $this->actingAs($this->admin)->postJson(route('admin.backup.run'));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect($data['log']['encrypted'])->toBeTrue();
    expect($data['log']['status'])->toBe('success');

    $filename = $data['log']['filename'];
    $filePath = storage_path('app/backups/'.$filename);
    expect(file_exists($filePath))->toBeTrue();

    // Verify correct decryption via verify endpoint
    $verifyResponse = $this->actingAs($this->admin)->postJson(route('admin.backup.verify'), [
        'filename' => $filename,
        'passphrase' => $rawKey,
    ]);

    $verifyResponse->assertStatus(200);
    $verifyData = $verifyResponse->json();
    expect($verifyData['success'])->toBeTrue();
    expect($verifyData['report']['has_db_dump'])->toBeTrue();

    // Verify decryption failure with wrong password
    $verifyFailResponse = $this->actingAs($this->admin)->postJson(route('admin.backup.verify'), [
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

test('admin can view backup log details via AJAX', function () {
    $log = BackupLog::create([
        'filename' => 'backup_test.zip',
        'type' => 'Full Backup',
        'size' => 1048576, // 1 MB
        'encrypted' => false,
        'status' => 'success',
        'duration' => 1.25,
        'drive_uploaded' => false,
    ]);

    $response = $this->actingAs($this->admin)->getJson(route('admin.backup.log-details', ['id' => $log->id]));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect($data['log']['id'])->toBe($log->id);
    expect($data['formatted_size'])->toBe('1 MB');
});

test('admin gets 404 for non-existent backup log details', function () {
    $response = $this->actingAs($this->admin)->getJson(route('admin.backup.log-details', ['id' => 9999]));
    $response->assertStatus(404);
});

test('admin can fetch storage directories via AJAX', function () {
    $publicStorage = storage_path('app/public');
    if (! file_exists($publicStorage)) {
        mkdir($publicStorage, 0755, true);
    }

    $tempTestDir = $publicStorage.'/test_folder_scan';
    if (! file_exists($tempTestDir)) {
        mkdir($tempTestDir, 0755, true);
    }
    file_put_contents($tempTestDir.'/test.txt', 'hello');

    $response = $this->actingAs($this->admin)->getJson(route('admin.backup.storage-directories'));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect($data['directories'])->toBeArray();

    $dirNames = array_column($data['directories'], 'name');
    expect($dirNames)->toContain('test_folder_scan');

    unlink($tempTestDir.'/test.txt');
    rmdir($tempTestDir);
});

test('admin can save backup settings with selective storage folders', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.backup.settings'), [
        'schedule' => 'weekly',
        'cron_expression' => '0 0 * * 0',
        'enabled' => '1',
        'backup_db' => '1',
        'backup_storage' => '1',
        'storage_folders' => ['documents', 'photos'],
        'google_drive_enabled' => '0',
        'google_drive_folder_id' => '1234567890abcdef',
        'retention_days' => '45',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $settings = SecuritySetting::getValue('backup_settings');
    expect($settings['storage_folders'])->toBe(['documents', 'photos']);
});
