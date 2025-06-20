<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\Venue;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $user = User::first();
        $venues = Venue::all();
        $services = Service::all();

        $books = [
            [
                'user_id' => $user->id,
                'venue_id' => $venues->random()->id,
                'service_id' => $services->random()->id,
                'event_date' => Carbon::now()->addDays(30)->toDateString(),
                'event_time' => Carbon::now()->addDays(30)->setTime(18, 0, 0),
                'guests_count' => 150,
                'total_price' => 8500000.00,
                'status' => 'confirmed',
                'notes' => 'Katta to\'y marosimi. Barcha qulayliklar kerak.',
            ],
            [
                'user_id' => $user->id,
                'venue_id' => $venues->random()->id,
                'service_id' => $services->random()->id,
                'event_date' => Carbon::now()->addDays(45)->toDateString(),
                'event_time' => Carbon::now()->addDays(45)->setTime(19, 0, 0),
                'guests_count' => 80,
                'total_price' => 4200000.00,
                'status' => 'pending',
                'notes' => 'Kichik oilaviy tadbirlar.',
            ],
            [
                'user_id' => $user->id,
                'venue_id' => $venues->random()->id,
                'service_id' => $services->random()->id,
                'event_date' => Carbon::now()->addDays(60)->toDateString(),
                'event_time' => Carbon::now()->addDays(60)->setTime(17, 30, 0),
                'guests_count' => 200,
                'total_price' => 12000000.00,
                'status' => 'confirmed',
                'notes' => 'Premium xizmat kerak.',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
