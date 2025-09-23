<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $subcategories = Subcategory::all();
        $sellers = User::where('role', 'seller')->get();

        if ($sellers->count() < 1) {
            $this->command->info('Seeder Service dibatalkan: tidak ada seller.');
            return;
        }

        $totalServices = 50;
        $sellerCount = $sellers->count();

        for ($i = 1; $i <= $totalServices; $i++) {
            $subcategory = $subcategories->random();
            // Pilih seller secara bergilir dari 5 seller
            $seller = $sellers[$i % $sellerCount];

            Service::create([
                'slug' => Str::slug($subcategory->name . '-service-' . $i),
                'user_id' => $seller->id,
                'subcategory_id' => $subcategory->id,
                'title' => $subcategory->name . ' Service ' . $i,
                'description' => 'Deskripsi layanan ' . $subcategory->name . ' ke-' . $i,
                'price' => rand(50, 500) * 1000,
                'job_type' => ['Full-time', 'Part-time', 'Freelance'][array_rand(['Full-time', 'Part-time', 'Freelance'])],
                'experience' => ['Beginner', 'Intermediate', 'Expert'][array_rand(['Beginner', 'Intermediate', 'Expert'])],
                'industry' => $subcategory->category->name,
                'contact' => '0812' . rand(10000000, 99999999),
                'address' => 'Jl. Contoh Alamat No. ' . rand(1, 100),
                'images' => json_encode(['service' . $i . '_1.jpg', 'service' . $i . '_2.jpg']),
                'latitude' => -2.9761 + mt_rand(-1000, 1000) / 10000,
                'longitude' => 104.7754 + mt_rand(-1000, 1000) / 10000,
            ]);
        }

        $this->command->info("Seeder Service selesai membuat $totalServices layanan untuk $sellerCount seller.");
    }
}
