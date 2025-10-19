<?php
namespace App\Http\View\Composers;

use App\Models\MenuItem;
use App\Models\User;
use App\Settings\HeaderSettings;
use Illuminate\Support\Facades\Auth;
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

        // --- পছন্দের প্রোপার্টির সংখ্যা গণনার লজিক এখানে যোগ করা হলো ---
        $favoritePropertiesCount = 0;

        if (Auth::check()) {
            // ইউজারের আইডি দিয়ে ক্যাশ কী (cache key) তৈরি করা হচ্ছে
            $cacheKey = 'user_' . Auth::id() . '_favorites_count';

            // নির্দিষ্ট সময়ের জন্য সংখ্যাটি ক্যাশ করে রাখা হচ্ছে
            $favoritePropertiesCount = Cache::remember($cacheKey, now()->addMinutes(10), function () {
                $user = User::withCount('favoriteProperties')->find(Auth::id());
                return $user->favorite_properties_count ?? 0;
            });
        }
        // --- নতুন লজিক শেষ ---

        // আগের ভ্যারিয়েবলগুলোর সাথে নতুনটি যোগ করে দেওয়া হচ্ছে
        $view->with([
            'headerSettings' => $this->headerSettings,
            'menuItems' => $menuItems,
            'favoritePropertiesCount' => $favoritePropertiesCount, // <-- নতুন ডেটা
        ]);
    }
}
