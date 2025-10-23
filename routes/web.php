<?php

use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

//Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// একটি নির্দিষ্ট প্রপার্টির ডিটেইলস দেখানোর জন্য রাউট
//Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');
//
//Route::post('/properties/{property:slug}/increment-view', [PropertyController::class, 'incrementViewCount'])
//    ->name('properties.increment-view');

//Route::get('/blog', [PostController::class, 'index'])->name('blog.index');



//Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');

//Route::get('/about-us', [PageController::class, 'about'])->name('about');
//Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');

// এই রাউটটি সকল স্ট্যাটিক পেজ হ্যান্ডেল করবে
//Route::get('/page/{page:slug}', [PageController::class, 'show'])->name('page.show');

//Route::get('/faq', [PageController::class, 'faq'])->name('faq');

//Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');

//Route::get('/our-inspiration', [PageController::class, 'ourInspiration'])->name('inspiration');

//Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming-soon');

//Route::get('/map-view', [PageController::class, 'mapView'])->name('map.view');

Route::controller(PropertyController::class)->group(function () {
    // সব প্রপার্টি লিস্ট দেখানোর জন্য
    Route::get('/properties', 'index')->name('properties.index');

    // নির্দিষ্ট প্রপার্টির ডিটেইলস দেখানোর জন্য
    Route::get('/properties/{property:slug}', 'show')->name('properties.show');

    // ভিউ কাউন্ট বাড়ানোর জন্য
    Route::post('/properties/{property:slug}/increment-view', 'incrementViewCount')
        ->name('properties.increment-view');
});


Route::controller(PostController::class)->group(function (){
    Route::get('/blog', 'index')->name('blog.index');
    Route::get('/blog/category/{category:slug}', 'index')->name('blog.category');
    Route::get('/blog/{post:slug}', 'show')->name('blog.show');
});

//Route::get('/blog/category/{category:slug}', function (Category $category) {
//    return (new PostController())->index($category);
//})->name('blog.category');

Route::controller(PageController::class)->group(function (){
    Route::get('/map-view', 'mapView')->name('map.view');
    Route::get('/coming-soon', 'comingSoon')->name('coming-soon');
    Route::get('/our-inspiration', 'ourInspiration')->name('inspiration');
    Route::get('/testimonials', 'testimonials')->name('testimonials');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/page/{page:slug}', 'show')->name('page.show');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/contact-us', 'contact')->name('contact');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', ProfileController::class)->name('profile.show');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    Route::resource('listings', ListingController::class);

    Route::get('/identity-verification', function () {
        return view('pages.identity-verification'); // আমরা এই ভিউ ফাইলটি পরবর্তী ধাপে তৈরি করব
    })->name('identity.verification');
});


Route::get('/contact-us', ContactPageController::class)->name('contact');

// সব প্রোপার্টি টাইপ দেখানোর জন্য পেজ
Route::get('/property-types', [PropertyTypeController::class, 'index'])->name('property-types.index');
