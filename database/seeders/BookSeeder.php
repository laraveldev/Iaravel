<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Wedding Planner',
                'author' => 'Sarah Joy',
                'description' => 'A complete guide to planning the perfect wedding.',
                'published_year' => 2018,
                'genre' => 'Guide',
                'cover_image' => null,
            ],
            [
                'title' => 'Love & Traditions',
                'author' => 'Otabek Mirzayev',
                'description' => 'Stories and traditions of Uzbek weddings.',
                'published_year' => 2020,
                'genre' => 'Culture',
                'cover_image' => null,
            ],
            [
                'title' => 'Wedding Stories',
                'author' => 'Lola Karimova',
                'description' => 'Heartwarming wedding stories from around the world.',
                'published_year' => 2017,
                'genre' => 'Stories',
                'cover_image' => null,
            ],
            [
                'title' => 'Bridal Fashion',
                'author' => 'Dilnoza Akbarova',
                'description' => 'A look at wedding dresses and fashion trends.',
                'published_year' => 2019,
                'genre' => 'Fashion',
                'cover_image' => null,
            ],
            [
                'title' => 'Wedding Cuisine',
                'author' => 'Jasur Rahmatov',
                'description' => 'Traditional and modern wedding dishes.',
                'published_year' => 2021,
                'genre' => 'Cooking',
                'cover_image' => null,
            ],
        ];
        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
