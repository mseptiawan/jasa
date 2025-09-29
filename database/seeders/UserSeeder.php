<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name'        => 'Super Admin',
                'password'         => Hash::make('password'),
                'role'             => 'admin',
                'bio'              => 'Saya seorang administrator',
                'email_verified_at' => now(),
            ]
        );

        // Sellers
        $sellers = [
            ['full_name' => 'Johan', 'email' => 'johan@gmail.com'],
            ['full_name' => 'Ovan',  'email' => 'ovan@gmail.com'],
        ];

        foreach ($sellers as $seller) {
            User::updateOrCreate(
                ['email' => $seller['email']],
                [
                    'full_name'        => $seller['full_name'],
                    'password'         => Hash::make('password'),
                    'role'             => 'seller',
                    'bio'              => 'Saya seorang penyedia jasa',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Customers
        $customers = [
            ['full_name' => 'Ripa', 'email' => 'ripa@gmail.com'],
            ['full_name' => 'Sifa', 'email' => 'sifa@gmail.com'],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'full_name'        => $customer['full_name'],
                    'password'         => Hash::make('password'),
                    'role'             => 'customer',
                    'bio'              => null,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
