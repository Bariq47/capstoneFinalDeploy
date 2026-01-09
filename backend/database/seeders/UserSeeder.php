<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'username' => 'superadmin',
                'password' => Hash::make('password123'),
                'role'     => 'superAdmin',
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );
    }
}
