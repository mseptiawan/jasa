<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SellerUserSeeder extends Seeder
{
    public function run(): void
    {

        User::updateOrCreate(
            ['email' => 'seller@example.com'],
            [
                'full_name'        => 'Seller Example',
                'password'         => Hash::make('password123'),
                'role'             => 'seller',
                'email_verified_at' => now(),
            ]
        );
    }
}
