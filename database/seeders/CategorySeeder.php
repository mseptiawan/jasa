<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Service;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // =============================
        // Hapus data lama dengan aman
        // =============================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::truncate();         // hapus dulu services (child)
        Subcategory::truncate();     // hapus subcategories
        Category::truncate();        // hapus categories
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =============================
        // Kategori + Subkategori
        // =============================
        $categories = [
            // Fiverr Categories
            'Graphics & Design' => [
                'Logo & Branding',
                'Web & Mobile Design',
                'Illustration',
                'Art & Crafts',
                'Packaging Design',
                'Presentation Design'
            ],
            'Digital Marketing' => [
                'Social Media Marketing',
                'SEO',
                'Content Marketing',
                'Email Marketing',
                'Marketing Strategy',
                'Video Marketing'
            ],
            'Writing & Translation' => [
                'Article & Blog Writing',
                'Translation',
                'Creative Writing',
                'Copywriting',
                'Proofreading & Editing'
            ],
            'Video & Animation' => [
                'Video Editing',
                'Short Video Ads',
                'Animation',
                'Whiteboard & Explainer Videos',
                'Intros & Outros'
            ],
            'Music & Audio' => [
                'Voice Over',
                'Mixing & Mastering',
                'Music Production',
                'Sound Effects',
                'Audiobooks'
            ],
            'Programming & Tech' => [
                'Web Development',
                'Mobile App Development',
                'E-Commerce Development',
                'Game Development',
                'Cybersecurity & IT Support',
                'WordPress'
            ],
            'Business' => [
                'Virtual Assistant',
                'Data Entry',
                'Market Research',
                'Business Plans',
                'Financial Consulting'
            ],
            'Lifestyle' => [
                'Online Lessons',
                'Gaming',
                'Fitness Lessons',
                'Cooking & Recipes'
            ],
            'Industries' => [
                'Real Estate',
                'Fashion',
                'Health, Nutrition & Wellness',
                'Legal Services'
            ],

            // Lokal Categories
            'Perbaikan Elektronik' => [
                'Perbaikan TV',
                'Perbaikan AC',
                'Perbaikan Kulkas',
                'Perbaikan Mesin Cuci',
                'Servis Sound System'
            ],
            'Jasa Smartphone' => [
                'Root HP',
                'Custom ROM',
                'Install Aplikasi',
                'Optimasi Performa HP',
                'Perbaikan Software HP'
            ],
            'Jasa Akademik' => [
                'Buat Artikel',
                'Bimbingan Tugas Kuliah',
                'Konsultasi Skripsi',
                'Proofreading & Editing',
                'Jasa Presentasi'
            ],
            'Kreatif & Digital' => [
                'Desain Grafis',
                'Buat Website',
                'Artikel & Blog',
                'Video Editing',
                'Ilustrasi & Animasi',
                'Social Media Management'
            ],
            'Konsultasi & Lainnya' => [
                'Konsultasi Bisnis',
                'Konsultasi Keuangan',
                'Tips & Trik Online',
                'Jasa Receh Lainnya',
                'Coaching & Mentoring'
            ]
        ];

        // =============================
        // Insert kategori & subkategori
        // =============================
        foreach ($categories as $categoryName => $subcategories) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => 'Kategori ' . $categoryName
            ]);

            foreach ($subcategories as $sub) {
                // Untuk menghindari duplicate slug, tambahkan ID random kecil kalau perlu
                $slug = Str::slug($sub);

                if (Subcategory::where('slug', $slug)->exists()) {
                    $slug .= '-' . rand(100, 999);
                }

                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $sub,
                    'slug' => $slug,
                    'description' => 'Subkategori ' . $sub
                ]);
            }
        }
    }
}
