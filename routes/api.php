<?php

use App\Http\Controllers\Api\V1\VenueController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\BronController;
use App\Http\Controllers\Api\V1\BookController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.rate_limit'])->prefix('v1')->group(function () {

    Route::apiResource('venues', VenueController::class);    

    Route::apiResource('services', ServiceController::class);

    Route::apiResource('books', BookController::class);

    Route::apiResource('brons', BronController::class); 
    Route::patch('brons/{id}/confirm', [BronController::class, 'confirm']);
    Route::patch('brons/{id}/cancel', [BronController::class, 'cancel']);
});

