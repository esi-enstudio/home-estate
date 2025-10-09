<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\WishlistController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// একটি নির্দিষ্ট প্রপার্টির ডিটেইলস দেখানোর জন্য রাউট
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::post('/properties/{property:slug}/increment-view', [PropertyController::class, 'incrementViewCount'])
    ->name('properties.increment-view');

Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

Route::get('/blog/category/{category:slug}', function (Category $category) {
    return (new PostController())->index($category);
})->name('blog.category');

Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');

Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');

// এই রাউটটি সকল স্ট্যাটিক পেজ হ্যান্ডেল করবে
Route::get('/page/{page:slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
});

Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming-soon');
