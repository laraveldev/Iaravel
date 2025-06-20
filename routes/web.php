<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/venues', function () {
    return view('venues');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/books', function () {
    return view('books');
});
