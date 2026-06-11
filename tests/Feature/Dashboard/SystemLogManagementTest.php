<?php

use App\Models\BackupLog;
use App\Models\SystemLog;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);

    SystemLog::query()->delete();
    BackupLog::query()->delete();
    DB::table('failed_jobs')->truncate();

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole('super-admin');

    $this->siswa = User::factory()->create();
    $this->siswa->assignRole('siswa');
});

test('unauthenticated guest cannot access system logs page', function () {
    $this->get(route('super-admin.logs.index'))->assertRedirect(route('login'));
});

test('unauthorized users (siswa) cannot access system logs page', function () {
    $this->actingAs($this->siswa)->get(route('super-admin.logs.index'))->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can access logs index (activity tab by default)', function () {
    $log = SystemLog::create([
        'user_id' => $this->superAdmin->id,
        'log_type' => 'activity',
        'event' => 'updated',
        'model_type' => User::class,
        'model_id' => $this->superAdmin->id,
        'old_values' => ['name' => 'Old Name'],
        'new_values' => ['name' => 'New Name'],
        'description' => 'Memperbarui data User',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHPUnit',
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('super-admin.logs.index'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.security.logs.index')
        ->assertSee('Log Sistem')
        ->assertSee('Log Perubahan Data')
        ->assertSee('Memperbarui data User')
        ->assertSee($this->superAdmin->name);
});

test('authorized admin can access logs index (security tab)', function () {
    $log = SystemLog::create([
        'user_id' => $this->superAdmin->id,
        'log_type' => 'security',
        'event' => 'login_success',
        'description' => 'Berhasil masuk ke sistem',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHPUnit',
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('super-admin.logs.index', ['tab' => 'security']));

    $response->assertStatus(200)
        ->assertSee('Berhasil masuk ke sistem')
        ->assertSee('login_success');
});

test('authorized admin can access logs index (failed jobs tab)', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{}',
        'exception' => 'RuntimeException: Something went wrong in line 42',
        'failed_at' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('super-admin.logs.index', ['tab' => 'failed_jobs']));

    $response->assertStatus(200)
        ->assertSee('RuntimeException')
        ->assertSee('database')
        ->assertSee('default');
});

test('authorized admin can access logs index (backup tab)', function () {
    BackupLog::create([
        'filename' => 'backup-2026-05-26.zip',
        'type' => 'Database Backup',
        'size' => 204800, // 200 KB
        'encrypted' => false,
        'status' => 'success',
        'duration' => 0.5,
        'drive_uploaded' => false,
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('super-admin.logs.index', ['tab' => 'backup']));

    $response->assertStatus(200)
        ->assertSee('backup-2026-05-26.zip');
});

test('authorized admin can view activity detail via AJAX JSON route', function () {
    $log = SystemLog::create([
        'user_id' => $this->superAdmin->id,
        'log_type' => 'activity',
        'event' => 'updated',
        'model_type' => User::class,
        'model_id' => $this->superAdmin->id,
        'old_values' => ['name' => 'Old Name'],
        'new_values' => ['name' => 'New Name'],
        'description' => 'Memperbarui data User',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHPUnit',
    ]);

    $response = $this->actingAs($this->superAdmin)->getJson(route('super-admin.logs.activity.show', $log->id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'event' => 'UPDATED',
                'model' => 'User',
                'description' => 'Memperbarui data User',
                'ip_address' => '127.0.0.1',
                'diff' => [
                    [
                        'attribute' => 'name',
                        'old' => 'Old Name',
                        'new' => 'New Name',
                    ],
                ],
            ],
        ]);
});

test('authorized admin can view failed job details via AJAX JSON route', function () {
    $id = DB::table('failed_jobs')->insertGetId([
        'uuid' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{}',
        'exception' => 'RuntimeException: Mock test exception',
        'failed_at' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)->getJson(route('super-admin.logs.failed-job.show', $id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $id,
                'uuid' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
                'connection' => 'database',
                'queue' => 'default',
                'exception' => 'RuntimeException: Mock test exception',
            ],
        ]);
});

test('authorized admin receives 404 for non-existent failed job details', function () {
    $response = $this->actingAs($this->superAdmin)->getJson(route('super-admin.logs.failed-job.show', 99999));
    $response->assertStatus(404);
});

test('authorized admin can retry failed job', function () {
    Artisan::shouldReceive('call')
        ->once()
        ->with('queue:retry', ['id' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d'])
        ->andReturn(0);

    $id = DB::table('failed_jobs')->insertGetId([
        'uuid' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{}',
        'exception' => 'RuntimeException: Mock test exception',
        'failed_at' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)->post(route('super-admin.logs.failed-job.retry', $id));

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

test('authorized admin can delete failed job log entry', function () {
    Artisan::shouldReceive('call')
        ->once()
        ->with('queue:forget', ['id' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d'])
        ->andReturn(0);

    $id = DB::table('failed_jobs')->insertGetId([
        'uuid' => 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
        'connection' => 'database',
        'queue' => 'default',
        'payload' => '{}',
        'exception' => 'RuntimeException: Mock test exception',
        'failed_at' => now(),
    ]);

    $response = $this->actingAs($this->superAdmin)->delete(route('super-admin.logs.failed-job.destroy', $id));

    $response->assertRedirect();
    $response->assertSessionHas('success');
});
