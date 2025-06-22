<?php

use App\Http\Controllers\Api\V1\VenueController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\BronController;
use App\Http\Controllers\Api\V1\BookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Venues API
    Route::apiResource('venues', VenueController::class);
    
    // Services API
    Route::apiResource('services', ServiceController::class);
    
    // Books API
    Route::apiResource('brons', BronController::class);
    Route::apiResource('books', BookController::class);
    
    // Additional booking actions
    Route::patch('brons/{id}/confirm', [BronController::class, 'confirm']);
    Route::patch('brons/{id}/cancel', [BronController::class, 'cancel']);
});

