<?php

namespace App\Providers;

use App\Http\View\Composers\FooterComposer;
use App\Http\View\Composers\HeaderComposer;
use App\Http\View\Composers\HomeComposer;
use App\Http\View\Composers\MetaComposer;
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
        View::composer('partials._home-banner', HomeComposer::class);
        View::composer('partials._header', HeaderComposer::class);
        View::composer('partials._footer', FooterComposer::class);
        View::composer('partials._meta_tags', MetaComposer::class);
    }
}
