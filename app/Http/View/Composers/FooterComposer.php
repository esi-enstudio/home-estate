<?php

namespace App\Http\View\Composers;


use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Settings\FooterSettings;

class FooterComposer
{
    /**
     * The footer settings instance.
     * আমরা এখানে Dependency Injection ব্যবহার করব।
     * @var FooterSettings
     */
    protected FooterSettings $footerSettings;

    /**
     * Create a new footer composer.
     * Laravel স্বয়ংক্রিয়ভাবে FooterSettings ক্লাসটির একটি ইনস্ট্যান্স এখানে পাঠিয়ে দেবে।
     *
     * @param FooterSettings $footerSettings
     * @return void
     */
    public function __construct(FooterSettings $footerSettings)
    {
        $this->footerSettings = $footerSettings;
    }

    public function compose(View $view): void
    {
        // ফুটার পেজগুলো (Privacy Policy, Terms, etc.) ক্যাশ থেকে লোড করা হচ্ছে
        $footerPages = Cache::remember('footer_pages', now()->addDay(), function () {
            return Page::where('is_published', true)->get();
        });

        // ভিউয়ের সাথে '$footerSettings' এবং '$footerPages' নামে দুটি ভ্যারিয়েবল যুক্ত করা হচ্ছে
        $view->with('footerSettings', $this->footerSettings);
        $view->with('footerPages', $footerPages);
    }
}
