<?php


use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('home');});

Route::get('/venues', function () {return view('venues');});

Route::get('/services', function () {return view('services');});

Route::get('/brons', function () {return view('brons');});

Route::get('/books', function () {return view('books');});

Route::get('/venues/{id}', function ($id) {$venue = \App\Models\Venue::findOrFail($id);return view('venues.show', compact('venue'));})->name('venues.show');

Route::get('/books/{id}', function ($id) {$book = \App\Models\Book::findOrFail($id);return view('books.show', compact('book'));})->name('books.show');

Route::get('/services/{id}', function ($id) {$service = \App\Models\Service::findOrFail($id);return view('services.show', compact('service'));})->name('services.show');
