<?php
namespace App\Http\View\Composers;

use App\Models\Property;
use App\Models\Review;
use App\Settings\HomeBannerSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeComposer
{
    public function __construct(public HomeBannerSettings $bannerSettings) {}

    public function compose(View $view): void
    {
        // ব্যানার কার্ডের জন্য একটি ট্রেন্ডিং প্রপার্টি লোড করা হচ্ছে
        $bannerProperty = Cache::remember('banner_trending_property', now()->addMinutes(30), function () {
            // আমরা আমাদের নতুন 'trending' স্কোপটি ব্যবহার করছি
            return Property::trending()
                ->with('media')
                ->first();
        });

        // ১. রেটিং পরিসংখ্যান
        $ratingStats = Cache::remember('home_banner_rating_stats', now()->addHours(1), function () {
            $approvedReviews = Review::where('status', 'approved');
            return [
                'average' => round($approvedReviews->avg('rating') ?? 0, 1),
                'count' => $approvedReviews->count(),
            ];
        });

        // ২. অ্যাভাটার স্ট্যাকের জন্য সাম্প্রতিক রিভিউয়ার
        $recentReviewers = Cache::remember('home_banner_recent_reviewers', now()->addHours(1), function () {
            //ล่าสุด রিভিউ দেওয়া ৪ জন স্বতন্ত্র ব্যবহারকারীকে আনা হচ্ছে
            return Review::where('status', 'approved')
                ->with('user')
                ->latest()
                ->distinct('user_id')
                ->take(4)
                ->get()
                ->map(fn($review) => $review->user);
        });

        // সকল ডেটা ভিউয়ের সাথে যুক্ত করা হচ্ছে
        $view->with([
            'bannerSettings' => $this->bannerSettings,
            'bannerProperty' => $bannerProperty,
            'ratingStats' => $ratingStats,
            'recentReviewers' => $recentReviewers,
        ]);
    }
}
