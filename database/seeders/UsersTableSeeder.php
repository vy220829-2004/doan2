<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $staffRoleId = Role::where('name', 'staff')->value('id');
        $customerRoleId = Role::where('name', 'customer')->value('id');

        User::updateOrCreate(['email' => 'NgNhuY@example.com'], [
            'name' => 'Nguyen Nhu Y',
            'email' => 'NgNhuY@example.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0123456789',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Hanoi, Vietnam',
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'TuMinhMinh@example.com'], [
            'name' => 'Tu Minh Minh',
            'email' => 'TuMinhMinh@example.com',
            'password' => bcrypt('123456'),
            'phone_number' => '023456789',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Da Nang, Vietnam',
            'role_id' => $staffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'TruVyMinh@example.com'], [
            'name' => 'Tru Vy Minh',
            'email' => 'TruVyMinh@example.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0345678912',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Can Tho, Vietnam',
            'role_id' => $customerRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
