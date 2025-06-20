<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Service;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $homeData = Cache::remember('home_page_data', 3600, function () {
            return [
                'popular_venues' => Venue::active()->limit(6)->get(['id', 'name', 'location', 'capacity', 'price']),
                'popular_services' => Service::active()->limit(8)->get(['id', 'name', 'description', 'price', 'type']),
                'total_venues' => Venue::active()->count(),
                'total_services' => Service::active()->count(),
                'total_bookings' => Book::count(),
                'recent_bookings' => Book::with(['venue:id,name', 'service:id,name'])
                    ->latest()
                    ->limit(5)
                    ->get(['id', 'venue_id', 'service_id', 'event_date', 'status']),
            ];
        });

        return view('welcome', compact('homeData'));
    }
}
