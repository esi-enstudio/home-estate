<?php
namespace App\Http\View\Composers;

use App\Models\Property;
use App\Settings\HomepageSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeComposer
{
    public function __construct(public HomepageSettings $bannerSettings) {}

    public function compose(View $view): void
    {
        // ব্যানার কার্ডের জন্য একটি ট্রেন্ডিং প্রপার্টি লোড করা হচ্ছে
        $bannerProperty = Cache::remember('banner_trending_property', now()->addMinutes(30), function () {
            // === START: নতুন এবং উন্নত কোয়েরি ===
            // আমরা আমাদের নতুন 'trending' স্কোপটি ব্যবহার করছি
            return Property::trending()
                ->with('media')
                ->first();
            // === END ===
        });

        $view->with('bannerSettings', $this->bannerSettings);
        $view->with('bannerProperty', $bannerProperty);
    }
}
