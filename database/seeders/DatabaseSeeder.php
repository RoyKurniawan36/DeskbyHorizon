<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@deskbyhorizon.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Shop owner
        User::create([
            'name' => 'Shop Owner',
            'email' => 'owner@deskbyhorizon.com',
            'password' => Hash::make('password'),
            'role' => 'shop_owner',
        ]);

        // Regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@deskbyhorizon.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Sample products
        $products = [
            ['name' => 'Mechanical Keyboard', 'price' => 129.99, 'stock' => 50, 'category' => 'Keyboards'],
            ['name' => 'Wireless Mouse', 'price' => 79.99, 'stock' => 75, 'category' => 'Mice'],
            ['name' => '27" Monitor', 'price' => 399.99, 'stock' => 30, 'category' => 'Monitors'],
            ['name' => 'LED Desk Lamp', 'price' => 49.99, 'stock' => 100, 'category' => 'Lighting'],
            ['name' => 'Standing Desk', 'price' => 599.99, 'stock' => 20, 'category' => 'Furniture'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}