<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $staffRole = Role::where('name', 'staff')->first();

        $permissions = Permission::query()->get();

        if ($adminRole) {
            $adminRole->permissions()->sync($permissions->modelKeys());
        }

        if ($staffRole) {
            $staffPermissionIds = Permission::query()
                ->whereIn('name', ['manage products', 'manage contacts'])
                ->pluck('id');

            $staffRole->permissions()->sync($staffPermissionIds);
        }
    }
}
