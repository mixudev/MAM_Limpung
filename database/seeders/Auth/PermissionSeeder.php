<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * PermissionSeeder
 *
 * Creates all granular permissions organized by group.
 * Guard: 'web' (shared with 'api' via Sanctum's guard resolution).
 *
 * Convention: {action}-{resource} in kebab-case
 * Groups: users, roles, courses, grades, announcements, reports
 */
class PermissionSeeder extends Seeder
{
    /**
     * Permission definitions: [name, group, display_name, description]
     */
    private array $permissions = [
        // --- User Management ---
        ['view-users',    'users', 'View Users',    'View list and detail of users'],
        ['create-users',  'users', 'Create Users',  'Create new user accounts'],
        ['edit-users',    'users', 'Edit Users',    'Update user information and status'],
        ['delete-users',  'users', 'Delete Users',  'Soft-delete user accounts'],
        ['restore-users', 'users', 'Restore Users', 'Restore soft-deleted users'],
        ['assign-roles',  'users', 'Assign Roles',  'Assign roles to users'],

        // --- Role & Permission Management ---
        ['view-roles',       'roles', 'View Roles',       'View role list and assignments'],
        ['create-roles',     'roles', 'Create Roles',     'Create new roles'],
        ['edit-roles',       'roles', 'Edit Roles',       'Edit role permissions'],
        ['delete-roles',     'roles', 'Delete Roles',     'Delete roles'],
        ['view-permissions', 'roles', 'View Permissions', 'View all permissions'],

        // --- Dashboard Access ---
        ['access-super-admin-dashboard', 'dashboard', 'Super Admin Dashboard', 'Access super admin panel'],
        ['access-admin-dashboard',       'dashboard', 'Admin Dashboard',       'Access admin panel'],
        ['access-guru-dashboard',        'dashboard', 'Guru Dashboard',        'Access teacher panel'],
        ['access-siswa-dashboard',       'dashboard', 'Siswa Dashboard',       'Access student panel'],

        // --- Course Management ---
        ['view-courses',    'courses', 'View Courses',    'View all courses'],
        ['create-courses',  'courses', 'Create Courses',  'Create new courses'],
        ['edit-courses',    'courses', 'Edit Courses',    'Edit course content'],
        ['delete-courses',  'courses', 'Delete Courses',  'Delete courses'],
        ['enroll-courses',  'courses', 'Enroll Courses',  'Enroll students in courses'],
        ['teach-courses',   'courses', 'Teach Courses',   'Access course teaching features'],
        ['attend-courses',  'courses', 'Attend Courses',  'Access course as student'],

        // --- Grade Management ---
        ['view-grades',    'grades', 'View Grades',    'View grades'],
        ['create-grades',  'grades', 'Create Grades',  'Input grades'],
        ['edit-grades',    'grades', 'Edit Grades',    'Edit existing grades'],
        ['delete-grades',  'grades', 'Delete Grades',  'Delete grade records'],
        ['view-own-grades', 'grades', 'View Own Grades', 'View own grades only'],

        // --- Announcements ---
        ['view-announcements',    'announcements', 'View Announcements',    'View announcements'],
        ['create-announcements',  'announcements', 'Create Announcements',  'Create announcements'],
        ['edit-announcements',    'announcements', 'Edit Announcements',    'Edit announcements'],
        ['delete-announcements',  'announcements', 'Delete Announcements',  'Delete announcements'],

        // --- Reports ---
        ['view-reports',   'reports', 'View Reports',   'View generated reports'],
        ['export-reports', 'reports', 'Export Reports', 'Export reports to file'],
        ['generate-reports', 'reports', 'Generate Reports', 'Run report generation'],
    ];

    public function run(): void
    {
        $this->command->info('🔐 Seeding permissions...');

        foreach ($this->permissions as [$name, $group, $displayName, $description]) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                [
                    'group'        => $group,
                    'display_name' => $displayName,
                    'description'  => $description,
                ]
            );
        }

        $this->command->info('✅ ' . count($this->permissions) . ' permissions seeded.');
    }
}
