<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Customer 1
        User::updateOrCreate(
            ['email' => 'customer1@example.com'],
            [
                'full_name'        => 'Customer One',
                'password'         => Hash::make('password123'),
                'role'             => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // Customer 2
        User::updateOrCreate(
            ['email' => 'customer2@example.com'],
            [
                'full_name'        => 'Customer Two',
                'password'         => Hash::make('password123'),
                'role'             => 'customer',
                'email_verified_at' => now(),
            ]
        );
    }
}
