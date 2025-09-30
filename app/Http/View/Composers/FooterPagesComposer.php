<?php

namespace App\Http\View\Composers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FooterPagesComposer
{
    public function compose(View $view): void
    {
        // ২৪ ঘণ্টার জন্য পেজের তালিকা ক্যাশ করা হবে
        $footerPages = Cache::remember('footer_pages', now()->addDay(), function () {
            return Page::where('is_published', true)->get();
        });

        // '$footerPages' নামে একটি ভ্যারিয়েবল ভিউয়ের সাথে যুক্ত করা হচ্ছে
        $view->with('footerPages', $footerPages);
    }
}
