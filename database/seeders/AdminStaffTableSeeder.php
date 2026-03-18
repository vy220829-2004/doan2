<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminStaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $staffRoleId = Role::where('name', 'staff')->value('id');

        User::updateOrCreate(['email' => 'AdminUser@example.com'], [
            'name' => 'Admin User',
            'email' => 'AdminUser@example.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0888888888',
            'status' => 'active',
            'avatar' => '',
            'address' => 'Can Tho, Vietnam',
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'StaffUser@example.com'], [
            'name' => 'Staff User',
            'email' => 'StaffUser@example.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0999999999',
            'status' => 'active',
            'avatar' => '',
            'address' => 'Can Tho, Vietnam',
            'role_id' => $staffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
