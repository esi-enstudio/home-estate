<?php

use App\Http\Controllers\PostController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/properties', [PostController::class, 'index'])->name('properties.index');

// একটি নির্দিষ্ট প্রপার্টির ডিটেইলস দেখানোর জন্য রাউট
Route::get('/properties/{property:slug}', [PostController::class, 'show'])->name('properties.show');

Route::post('/properties/{property:slug}/increment-view', [PostController::class, 'incrementViewCount'])
    ->name('properties.increment-view');

Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

Route::get('/blog/category/{category:slug}', function (Category $category) {
    return (new PostController())->index($category);
})->name('blog.category');

Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');
