<?php

namespace App\Providers;

use App\Http\View\Composers\FooterPagesComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // যখনই 'partials._footer' ভিউটি রেন্ডার হবে,
        // FooterPagesComposer ক্লাসটি তার compose() মেথডটি চালাবে।
        View::composer('partials._footer', FooterPagesComposer::class);
    }
}
