<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SellerUserSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = [
            [
                'full_name' => 'Seller Example 1',
                'email' => 'seller1@example.com',
            ],
            [
                'full_name' => 'Seller Example 2',
                'email' => 'seller2@example.com',
            ],
            [
                'full_name' => 'Seller Example 3',
                'email' => 'seller3@example.com',
            ],
        ];

        foreach ($sellers as $seller) {
            User::updateOrCreate(
                ['email' => $seller['email']],
                [
                    'full_name'        => $seller['full_name'],
                    'password'         => Hash::make('password123'),
                    'role'             => 'seller',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
