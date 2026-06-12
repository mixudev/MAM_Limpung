<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * RoleSeeder
 *
 * Creates roles and assigns permissions per role.
 *
 * Role Hierarchy (level = privilege, higher = more powerful):
 *   super-admin (100) → Everything
 *   admin       (50)  → User & content management (no role management)
 *   guru        (20)  → Teaching, grading
 *   siswa       (10)  → Attending courses, viewing own data
 *
 * Design principle: Assign permissions to roles, not users directly,
 * unless a specific user needs an exception.
 */
class RoleSeeder extends Seeder
{
    private array $roles = [
        [
            'name' => 'super-admin',
            'display_name' => 'Super Administrator',
            'description' => 'Full system access. Manages roles and permissions.',
            'level' => 100,
            'permissions' => '*', // Wildcard = all permissions
        ],
        [
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Manages users, courses, announcements. No role/permission management.',
            'level' => 50,
            'permissions' => [
                'access-admin-dashboard',
                'view-users', 'create-users', 'edit-users', 'delete-users', 'restore-users',
                'view-courses', 'create-courses', 'edit-courses', 'delete-courses', 'enroll-courses',
                'view-grades', 'create-grades', 'edit-grades', 'delete-grades',
                'view-announcements', 'create-announcements', 'edit-announcements', 'delete-announcements',
                'view-article-categories', 'create-article-categories', 'edit-article-categories', 'delete-article-categories',
                'view-articles', 'create-articles', 'edit-articles', 'delete-articles',
                'view-reports', 'export-reports', 'generate-reports',
                'view-achievements', 'create-achievements', 'edit-achievements', 'delete-achievements',
                'view-galeri', 'create-galeri', 'edit-galeri', 'delete-galeri', 'approve-galeri',
                'manage-chatbot',
            ],
        ],
        [
            'name' => 'guru',
            'display_name' => 'Guru',
            'description' => 'Teaches courses, inputs grades, views student reports.',
            'level' => 20,
            'permissions' => [
                'access-guru-dashboard',
                'view-users',
                'view-courses', 'teach-courses',
                'view-grades', 'create-grades', 'edit-grades',
                'view-announcements', 'create-announcements',
                'view-article-categories',
                'view-articles', 'create-articles', 'edit-articles', 'delete-articles',
                'view-reports',
                'view-galeri', 'create-galeri', 'edit-galeri', 'delete-galeri',
            ],
        ],
        [
            'name' => 'siswa',
            'display_name' => 'Siswa',
            'description' => 'Attends courses, views own grades and announcements.',
            'level' => 10,
            'permissions' => [
                'access-siswa-dashboard',
                'attend-courses',
                'view-own-grades',
                'view-announcements',
                'view-galeri', 'create-galeri', 'edit-galeri', 'delete-galeri',
                'view-articles', 'create-articles', 'edit-articles', 'delete-articles',
            ],
        ],
    ];

    public function run(): void
    {
        $this->command->info('👥 Seeding roles...');

        $allPermissions = Permission::where('guard_name', 'web')
            ->pluck('name')
            ->toArray();

        foreach ($this->roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => 'web'],
                $roleData
            );

            // Update fields if already exists
            $role->update([
                'display_name' => $roleData['display_name'],
                'description' => $roleData['description'],
                'level' => $roleData['level'],
            ]);

            // Sync permissions
            if ($permissions === '*') {
                $role->syncPermissions($allPermissions);
            } else {
                $role->syncPermissions($permissions);
            }

            $count = $permissions === '*' ? count($allPermissions) : count($permissions);
            $this->command->line("  → {$role->name}: {$count} permissions assigned.");
        }

        $this->command->info('✅ '.count($this->roles).' roles seeded.');
    }
}
