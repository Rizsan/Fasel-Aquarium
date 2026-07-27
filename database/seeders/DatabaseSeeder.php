<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
        WebsiteSettingSeeder::class, ]);
        // Seed Users
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'admin@toko.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Budi Santoso',
            'email'     => 'budi@toko.com',
            'password'  => Hash::make('password123'),
            'role'      => 'user',
            'is_active' => true,
        ]);

        // Seed Products
        $products = [
            [
                'name'        => 'Laptop Gaming Pro',
                'price'       => 15000000,
                'image'       => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=400',
                'description' => 'Laptop gaming performa tinggi dengan RTX 4060.',
                'stock'       => 10,
                'is_active'   => true,
            ],
            [
                'name'        => 'Wireless Headphone',
                'price'       => 850000,
                'image'       => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
                'description' => 'Headphone premium dengan noise cancelling.',
                'stock'       => 25,
                'is_active'   => true,
            ],
            [
                'name'        => 'Mechanical Keyboard',
                'price'       => 1200000,
                'image'       => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400',
                'description' => 'Keyboard mekanikal RGB dengan switch red.',
                'stock'       => 15,
                'is_active'   => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
