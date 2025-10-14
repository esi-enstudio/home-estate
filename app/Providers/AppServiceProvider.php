<?php

namespace App\Providers;

use App\Models\Faq;
use App\Models\MenuItem;
use App\Models\Post;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Review;
use App\Observers\FaqObserver;
use App\Observers\MenuItemObserver;
use App\Observers\PostObserver;
use App\Observers\PropertyObserver;
use App\Observers\PropertyTypeObserver;
use App\Observers\ReviewObserver;
use Filament\Support\Facades\FilamentView;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Review::observe(ReviewObserver::class);
        Property::observe(PropertyObserver::class);
        PropertyType::observe(PropertyTypeObserver::class);
        Faq::observe(FaqObserver::class);
        Post::observe(PostObserver::class);
        MenuItem::observe(MenuItemObserver::class);
    }
}
