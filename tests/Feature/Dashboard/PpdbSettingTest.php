<?php

use App\Models\AcademicYear;
use App\Models\PpdbSetting;
use App\Models\RegistrationWave;
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
    $response->assertStatus(302)->assertRedirect(route('frontend.home'));
});

test('authorized admin can access ppdb settings page and see defaults', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.ppdb.settings.edit'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.settings')
        ->assertSee('Pengaturan')
        ->assertSee('Konfigurasi PPDB')
        ->assertSee('Tahun Ajaran')
        ->assertSee('Nama Wali');
});

test('authorized admin can update general ppdb is_open toggle', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.ppdb.settings.general'), [
        'is_open' => '0',
    ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $config = PpdbSetting::getValue('general');
    $this->assertFalse($config['is_open']);
});

test('authorized admin can create a new academic year', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.ppdb.settings.years.store'), [
        'year' => '2028',
    ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('academic_years', [
        'year' => 2028,
        'name' => '2028/2029',
    ]);
});

test('authorized admin can view academic year detail page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $ay = AcademicYear::factory()->create(['year' => 2028]);

    $response = $this->actingAs($admin)->get(route('admin.ppdb.settings.years.show', $ay->id));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.ppdb.year-detail')
        ->assertSee($ay->name)
        ->assertSee('Gelombang Pendaftaran');
});

test('authorized admin can activate a different academic year', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    AcademicYear::factory()->create(['year' => 2027, 'is_active' => false]);
    $target = AcademicYear::factory()->create(['year' => 2028, 'is_active' => false]);

    $response = $this->actingAs($admin)
        ->withHeaders(['Referer' => route('admin.ppdb.settings.edit')])
        ->post(route('admin.ppdb.settings.years.activate', $target->id));

    $response->assertRedirect();

    $this->assertFalse(AcademicYear::where('year', 2027)->first()->is_active);
    $this->assertTrue($target->fresh()->is_active);
});

test('authorized admin can create a wave for an academic year', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $ay = AcademicYear::factory()->create(['year' => 2028]);

    $response = $this->actingAs($admin)
        ->withHeaders(['Referer' => route('admin.ppdb.settings.edit')])
        ->post(route('admin.ppdb.settings.waves.store'), [
            'academic_year_id' => $ay->id,
            'name' => 'Gelombang 1',
            'start_date' => '2028-01-01',
            'end_date' => '2028-06-30',
        ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('registration_waves', [
        'academic_year_id' => $ay->id,
        'name' => 'Gelombang 1',
        'slug' => 'gelombang-1',
    ]);
});

test('authorized admin can update a wave', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $ay = AcademicYear::factory()->create(['year' => 2028]);
    $wave = RegistrationWave::factory()->create([
        'academic_year_id' => $ay->id,
        'name' => 'Gelombang 1',
        'start_date' => '2028-01-01',
        'end_date' => '2028-06-30',
    ]);

    $response = $this->actingAs($admin)
        ->withHeaders(['Referer' => route('admin.ppdb.settings.edit')])
        ->put(route('admin.ppdb.settings.waves.update', $wave->id), [
            'name' => 'Gelombang 1 (Revisi)',
            'start_date' => '2028-02-01',
            'end_date' => '2028-07-31',
            'is_active' => '0',
        ]);

    $response->assertRedirect();

    $wave->refresh();
    $this->assertEquals('Gelombang 1 (Revisi)', $wave->name);
    $this->assertFalse($wave->is_active);
});

test('authorized admin can delete a wave', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $ay = AcademicYear::factory()->create(['year' => 2028]);
    $wave = RegistrationWave::factory()->create([
        'academic_year_id' => $ay->id,
    ]);

    $response = $this->actingAs($admin)
        ->withHeaders(['Referer' => route('admin.ppdb.settings.edit')])
        ->delete(route('admin.ppdb.settings.waves.destroy', $wave->id));

    $response->assertRedirect();
    $this->assertModelMissing($wave);
});

test('authorized admin can delete an academic year with no waves', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $ay = AcademicYear::factory()->create(['year' => 2028, 'is_active' => false]);

    $response = $this->actingAs($admin)->delete(route('admin.ppdb.settings.years.destroy', $ay->id));

    $response->assertRedirect(route('admin.ppdb.settings.edit'));
    $this->assertModelMissing($ay);
});

test('authorized admin cannot delete an academic year with waves', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $ay = AcademicYear::factory()->create(['year' => 2028, 'is_active' => false]);
    RegistrationWave::factory()->create(['academic_year_id' => $ay->id]);

    $response = $this->actingAs($admin)->delete(route('admin.ppdb.settings.years.destroy', $ay->id));

    $response->assertSessionHasErrors();
    $this->assertDatabaseHas('academic_years', ['id' => $ay->id]);
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
                'is_active' => '1',
            ],
            [
                'id' => 'kartu_kip',
                'label' => 'Kartu KIP (Jika Ada)',
                'required' => '0',
                'is_active' => '0',
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.ppdb.settings.edit'))
        ->assertSessionHas('success');

    $reqs = PpdbSetting::getValue('requirements');
    $this->assertCount(2, $reqs);
    $this->assertEquals('scan_skhun', $reqs[0]['id']);
    $this->assertTrue($reqs[0]['required']);
    $this->assertTrue($reqs[0]['is_active']);
    $this->assertFalse($reqs[1]['required']);
    $this->assertFalse($reqs[1]['is_active']);
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
                'is_active' => '1',
            ],
            [
                'id' => 'jalur_prestasi',
                'label' => 'Jalur Prestasi',
                'type' => 'select',
                'options' => 'Olahraga, Seni, Tahfidz',
                'required' => '0',
                'is_active' => '0',
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
    $this->assertFalse($fields[1]['is_active']);
});
