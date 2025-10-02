<?php
namespace App\Http\View\Composers;

use App\Models\MenuItem;
use App\Settings\HeaderSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HeaderComposer
{
    public function __construct(public HeaderSettings $headerSettings) {}

    public function compose(View $view): void
    {
        $menuItems = Cache::remember('main_menu', now()->addDay(), function () {
            return MenuItem::where('is_active', true)
                ->whereNull('parent_id') // শুধুমাত্র টপ-লেভেল আইটেমগুলো আনা হচ্ছে
                ->with('recursiveChildren')
                ->orderBy('sort_order')
                ->get();
        });

        $view->with('headerSettings', $this->headerSettings);
        $view->with('menuItems', $menuItems);
    }
}
