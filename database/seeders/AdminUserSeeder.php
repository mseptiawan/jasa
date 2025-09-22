<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // cek kalau sudah ada
            [
                'full_name' => 'Super Admin',
                'password' => Hash::make('password123'), // ganti sesuai kebutuhan
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
