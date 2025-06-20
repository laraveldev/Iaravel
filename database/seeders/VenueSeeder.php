<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Chilonzor',
                'location' => 'Chilonzor tumani, Toshkent',
                'capacity' => 200,
                'price' => 5000000.00,
                'description' => 'Zamonaviy to\'y zali, barcha qulayliklar bilan',
                'phone' => '+998901234567',
                'email' => 'chilonzor@venue.uz',
                'is_active' => true,
            ],
            [
                'name' => 'Yunusobod Palace',
                'location' => 'Yunusobod tumani, Toshkent',
                'capacity' => 300,
                'price' => 8000000.00,
                'description' => 'Katta to\'y zali, 300 kishigacha sig\'adi',
                'phone' => '+998901234568',
                'email' => 'yunusobod@venue.uz',
                'is_active' => true,
            ],
            [
                'name' => 'Sergeli Wedding Hall',
                'location' => 'Sergeli tumani, Toshkent',
                'capacity' => 150,
                'price' => 3500000.00,
                'description' => 'Kichik, lekin shinam to\'y zali',
                'phone' => '+998901234569',
                'email' => 'sergeli@venue.uz',
                'is_active' => true,
            ],
            [
                'name' => 'Mirzo Ulugbek Grand Hall',
                'location' => 'Mirzo Ulugbek tumani, Toshkent',
                'capacity' => 400,
                'price' => 12000000.00,
                'description' => 'Eng katta va hashamatli to\'y zali',
                'phone' => '+998901234570',
                'email' => 'mirzoulugbek@venue.uz',
                'is_active' => true,
            ],
            [
                'name' => 'Bektemir Lux',
                'location' => 'Bektemir tumani, Toshkent',
                'capacity' => 120,
                'price' => 2800000.00,
                'description' => 'Ekonom sinf to\'y zali',
                'phone' => '+998901234571',
                'email' => 'bektemir@venue.uz',
                'is_active' => true,
            ],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}
