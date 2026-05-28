<?php

use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('unauthenticated guest cannot access achievements import page or action', function () {
    $this->get(route('admin.prestasi.import.page'))->assertRedirect(route('login'));
    $this->post(route('admin.prestasi.save-preview'), ['data' => []])->assertRedirect(route('login'));
});

test('authorized admin can access achievements import page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.prestasi.import.page'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.prestasi.import')
        ->assertSee('Import Data Prestasi');
});

test('admin can successfully import achievements from preview data', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $data = [
        'data' => [
            [
                'row_number' => 5,
                'tanggal' => '2026-05-19',
                'tahun' => 2026,
                'peraih' => 'Ahmad Fauzi',
                'judul' => 'Juara 1 Lomba Pidato',
                'juara' => 'Juara 1',
                'tingkat' => 'Kabupaten',
                'jenis' => 'Non-Akademik',
                'penyelenggara' => 'Kemenag',
                'unggulan' => 'Ya',
                'deskripsi' => 'Prestasi pidato bahasa arab.',
            ],
            [
                'row_number' => 6,
                'tanggal' => '2026-05-20',
                'tahun' => 2026,
                'peraih' => 'Siti Aminah',
                'judul' => 'Olimpiade Matematika Nasional',
                'juara' => 'Medali Emas',
                'tingkat' => 'Nasional',
                'jenis' => 'Akademik',
                'penyelenggara' => 'Kemendikbud',
                'unggulan' => 'Tidak',
                'deskripsi' => 'Olimpiade bidang matematika.',
            ],
        ],
    ];

    $response = $this->actingAs($admin)->postJson(route('admin.prestasi.save-preview'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'imported_count' => 2,
            'failed_rows' => [],
        ]);

    $this->assertDatabaseHas('prestasis', [
        'judul' => 'Juara 1 Lomba Pidato',
        'peraih' => 'Ahmad Fauzi',
        'tingkat' => 'kabupaten',
        'jenis' => 'non_akademik',
        'is_featured' => true,
    ]);

    $this->assertDatabaseHas('prestasis', [
        'judul' => 'Olimpiade Matematika Nasional',
        'peraih' => 'Siti Aminah',
        'tingkat' => 'nasional',
        'jenis' => 'akademik',
        'is_featured' => false,
    ]);
});

test('admin import handles partial failures and returns detailed error reasons', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $data = [
        'data' => [
            [
                'row_number' => 5,
                'tanggal' => '2026-05-19',
                'tahun' => 2026,
                'peraih' => 'Ahmad Fauzi',
                'judul' => 'Juara 1 Lomba Pidato',
                'juara' => 'Juara 1',
                'tingkat' => 'Kabupaten',
                'jenis' => 'Non-Akademik',
                'penyelenggara' => 'Kemenag',
                'unggulan' => 'Ya',
                'deskripsi' => 'Prestasi pidato.',
            ],
            [
                // Invalid: empty judul & invalid tingkat
                'row_number' => 6,
                'tanggal' => '',
                'tahun' => 2026,
                'peraih' => 'Siti Aminah',
                'judul' => '',
                'juara' => 'Medali Emas',
                'tingkat' => 'Planet',
                'jenis' => 'Akademik',
                'penyelenggara' => 'Kemendikbud',
                'unggulan' => 'Tidak',
                'deskripsi' => 'Olimpiade.',
            ],
        ],
    ];

    $response = $this->actingAs($admin)->postJson(route('admin.prestasi.save-preview'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => false,
            'imported_count' => 1,
        ]);

    $result = $response->json();
    expect($result['failed_rows'])->toHaveCount(1);
    expect($result['failed_rows'][0]['row_number'])->toBe(6);
    expect($result['failed_rows'][0]['errors'])->toContain('Judul Prestasi tidak boleh kosong.');
    expect($result['failed_rows'][0]['errors'])->toContain('Tingkat tidak valid (Pilihan: Sekolah, Kabupaten, Provinsi, Nasional, Internasional).');

    // Fauzi should be saved
    $this->assertDatabaseHas('prestasis', [
        'judul' => 'Juara 1 Lomba Pidato',
        'peraih' => 'Ahmad Fauzi',
    ]);

    // Siti Aminah should not be saved
    $this->assertDatabaseMissing('prestasis', [
        'peraih' => 'Siti Aminah',
    ]);
});
