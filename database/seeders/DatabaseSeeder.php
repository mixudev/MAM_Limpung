<?php

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Database\Seeders\Auth\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters:
     *   1. Permissions must exist before roles can sync them.
     *   2. Roles must exist before users can be assigned them.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PpdbSettingSeeder::class,
            ArticleCategorySeeder::class,
            ArticleSeeder::class,
            AnnouncementSeeder::class,
            PrestasiSeeder::class,
        ]);
    }
}
