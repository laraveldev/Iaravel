<?php

namespace App\Console\Commands;

use App\Models\Venue;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RefreshCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:refresh-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh cache for venues and services daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cache refresh...');

        // Clear old cache
        Cache::forget('venues_list');
        Cache::forget('services_list');
        
        // Individual venue caches
        $venues = Venue::all();
        foreach ($venues as $venue) {
            Cache::forget("venue_{$venue->id}");
        }

        // Refresh venues cache
        Cache::remember('venues_list', 3600, function () {
            return Venue::active()->get(['id', 'name', 'location', 'capacity', 'price']);
        });

        // Refresh services cache
        Cache::remember('services_list', 3600, function () {
            return Service::active()->get(['id', 'name', 'description', 'price', 'type']);
        });

        $this->info('Cache refreshed successfully!');
        
        return Command::SUCCESS;
    }
}
