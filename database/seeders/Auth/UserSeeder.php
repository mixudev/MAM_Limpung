<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 *
 * Creates one test user per role for development/testing.
 *
 * Credentials:
 *   super-admin@example.com   / SuperAdmin123!
 *   admin@example.com         / Admin123!
 *   guru@example.com          / Guru123!
 *   siswa@example.com         / Siswa123!
 *
 * NEVER run this seeder in production. Gate with environment check.
 */
class UserSeeder extends Seeder
{
    private array $users = [
        [
            'name' => 'Super Administrator',
            'email' => 'super-admin@example.com',
            'password' => 'SuperAdmin123!',
            'role' => 'super-admin',
        ],
        [
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => 'Admin123!',
            'role' => 'admin',
        ],
        [
            'name' => 'Budi Santoso (Guru)',
            'email' => 'guru@example.com',
            'password' => 'Guru123!',
            'role' => 'guru',
        ],
        [
            'name' => 'Andi Wijaya (Siswa)',
            'email' => 'siswa@example.com',
            'password' => 'Siswa123!',
            'role' => 'siswa',
        ],
    ];

    public function run(): void
    {
        // Safety guard: refuse to run in production
        if (app()->isProduction()) {
            $this->command->error('❌ UserSeeder tidak dapat dijalankan di environment production!');

            return;
        }

        $this->command->info('👤 Seeding users...');

        foreach ($this->users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            // syncRoles replaces all existing roles — safe for re-seeding
            $user->syncRoles([$data['role']]);

            $this->command->line("  → {$user->email} [{$data['role']}] created/updated.");
        }

        $this->command->info('✅ '.count($this->users).' users seeded.');
        $this->command->newLine();
        $this->command->table(
            ['Email', 'Password', 'Role'],
            collect($this->users)->map(fn ($u) => [$u['email'], $u['password'], $u['role']])->toArray()
        );
    }
}
