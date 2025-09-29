<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\User;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Service::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Hapus service lama hanya milik Johan
        $johan = User::where('full_name', 'Johan')->first();
        if (!$johan) {
            $this->command->info('Seeder batal: seller Johan tidak ditemukan.');
            return;
        }
        Service::where('user_id', $johan->id)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $subcategories = Subcategory::all();
        if ($subcategories->isEmpty()) {
            $this->command->info('Seeder batal: subcategory kosong.');
            return;
        }

        $industries = ['IT', 'Kesehatan', 'Pendidikan', 'Jasa', 'Lainnya'];
        $dummyDescription = 'Layanan ini dibuat untuk memberikan pengalaman terbaik bagi pelanggan dengan standar kualitas tinggi. '
            . 'Setiap detail pekerjaan dikerjakan dengan penuh ketelitian dan tanggung jawab agar hasil akhir memuaskan. '
            . 'Kami mengutamakan komunikasi yang jelas, waktu pengerjaan yang tepat, dan fleksibilitas terhadap kebutuhan pengguna. '
            . 'Dengan dukungan pengalaman dan keterampilan yang memadai, layanan ini cocok untuk berbagai kalangan yang '
            . 'mengutamakan kepraktisan serta hasil yang profesional. Kepuasan Anda adalah prioritas utama kami setiap saat.';
        $judulJasa = [
            'Jasa Les Piano',
            'Coding Website',
            'Root HP Android',
            'Service AC Rumah',
            'Perbaikan TV LED',
            'Desain Logo',
            'Service Laptop',
            'Install Windows & Linux',
            'Les Matematika',
            'Jasa Foto Produk'
        ];

        foreach ($judulJasa as $key => $judul) {
            $subcategory = $subcategories->random();

            Service::create([
                'slug'           => Str::slug('johan-' . $judul),
                'user_id'        => $johan->id,
                'subcategory_id' => $subcategory->id,
                'title'          => $judul,
                'description'    => $dummyDescription,
                'price'          => rand(50, 300) * 1000,
                'job_type'       => ['Full-time', 'Part-time', 'Freelance'][array_rand([0, 1, 2])],
                'experience'     => ['Beginner', 'Intermediate', 'Expert'][array_rand([0, 1, 2])],
                'industry'       => $industries[array_rand($industries)],
                'contact'        => '0812' . rand(10000000, 99999999),
                'address'        => 'Jl. Contoh Palembang No. ' . rand(1, 100),
                'images'         => json_encode(['services/jasaimages.jpg']),
                'latitude'       => -2.9761 + mt_rand(-300, 300) / 10000,
                'longitude'      => 104.7754 + mt_rand(-300, 300) / 10000,
            ]);
        }

        $this->command->info('Seeder Service Johan selesai membuat 10 layanan.');
    }
}
