<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// একটি নির্দিষ্ট প্রপার্টির ডিটেইলস দেখানোর জন্য রাউট
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::post('/properties/{property:slug}/increment-view', [PropertyController::class, 'incrementViewCount'])
    ->name('properties.increment-view');
