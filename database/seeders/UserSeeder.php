<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // User biasa
        User::create([
            'name'      => 'Rizu',
            'email'     => 'rizu@example.com',
            'password'  => Hash::make('password123'),
            'role'      => 'user',
            'is_active' => true,
        ]);
    }
}
