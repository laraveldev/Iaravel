<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Fotografiya',
                'description' => 'Professional to\'y suratga olish xizmati',
                'price' => 1500000.00,
                'type' => 'photography',
                'is_active' => true,
            ],
            [
                'name' => 'Videosurat',
                'description' => 'To\'yni videoga olish va montaj qilish',
                'price' => 2000000.00,
                'type' => 'videography',
                'is_active' => true,
            ],
            [
                'name' => 'Musiqa guruhi',
                'description' => 'Jonli musiqa ijrosi, 4 kishi',
                'price' => 3000000.00,
                'type' => 'music',
                'is_active' => true,
            ],
            [
                'name' => 'DJ xizmati',
                'description' => 'Professional DJ va audio jihozlar',
                'price' => 800000.00,
                'type' => 'music',
                'is_active' => true,
            ],
            [
                'name' => 'Gullar bezagi',
                'description' => 'To\'y zalini gullar bilan bezash',
                'price' => 1200000.00,
                'type' => 'decoration',
                'is_active' => true,
            ],
            [
                'name' => 'Transport xizmati',
                'description' => 'Kelin-kuyovni olib ketish uchun bezatilgan mashina',
                'price' => 500000.00,
                'type' => 'transport',
                'is_active' => true,
            ],
            [
                'name' => 'Oshpaz xizmati',
                'description' => 'Professional oshpazlar va xizmatchilar',
                'price' => 2500000.00,
                'type' => 'catering',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
