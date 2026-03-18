<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            PermissionsTablSeeder::class,
            RolePermissionTableSeeder::class,
            AdminStaffTableSeeder::class,
            UsersTableSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
