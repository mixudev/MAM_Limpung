<?php

use App\Models\PpdbSetting;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Database\Seeders\PpdbSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
    $this->seed(PpdbSettingSeeder::class);
});

test('guest or unauthorized users are blocked from ppdb settings', function () {
    $response = $this->get(route('admin.ppdb.settings.edit'));
    $response->assertRedirect(route('login'));

    $user = User::factory()->create();
    $user->assignRole('siswa');

    $response = $this->actingAs($user)->get(route('admin.ppdb.settings.edit'));
    $response->assertStatus(403);
});

test('authorized admin can access ppdb settings page and see defaults', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.ppdb.settings.edit'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.settings')
        ->assertSee('Pengaturan')
        ->assertSee('Konfigurasi PPDB')
        ->assertSee('Tahun Pelajaran Berjalan')
        ->assertSee('Nama Wali');
});

test('authorized admin can update general ppdb configs', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.ppdb.settings.general'), [
        'is_open' => '0', // Closed
        'tahun_ajaran' => '2028',
        'target_quota' => '150',
        'registration_fee' => '200000',
        'start_date' => '2028-06-01',
        'end_date' => '2028-09-15',
    ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $config = PpdbSetting::getValue('general');
    $this->assertFalse($config['is_open']);
    $this->assertEquals(2028, $config['tahun_ajaran']);
    $this->assertEquals(150, $config['target_quota']);
    $this->assertEquals(200000, $config['registration_fee']);
});

test('authorized admin can save dynamic document requirements checklist', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.ppdb.settings.requirements'), [
        'requirements' => [
            [
                'id' => 'scan_skhun',
                'label' => 'Scan SKHUN Asli',
                'required' => '1',
            ],
            [
                'id' => 'kartu_kip',
                'label' => 'Kartu KIP (Jika Ada)',
                'required' => '0',
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $reqs = PpdbSetting::getValue('requirements');
    $this->assertCount(2, $reqs);
    $this->assertEquals('scan_skhun', $reqs[0]['id']);
    $this->assertTrue($reqs[0]['required']);
    $this->assertFalse($reqs[1]['required']);
});

test('authorized admin can update custom dynamic form fields in batch', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.ppdb.settings.fields.update'), [
        'fields' => [
            [
                'id' => 'nama_wali',
                'label' => 'Nama Wali Murid',
                'type' => 'text',
                'required' => '1',
            ],
            [
                'id' => 'jalur_prestasi',
                'label' => 'Jalur Prestasi',
                'type' => 'select',
                'options' => 'Olahraga, Seni, Tahfidz',
                'required' => '0',
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $fields = PpdbSetting::getValue('form_fields');
    $this->assertCount(2, $fields);
    $this->assertEquals('nama_wali', $fields[0]['id']);
    $this->assertEquals('jalur_prestasi', $fields[1]['id']);
    $this->assertEquals(['Olahraga', 'Seni', 'Tahfidz'], $fields[1]['options']);
    $this->assertFalse($fields[1]['required']);
});
