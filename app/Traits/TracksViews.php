<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

trait TracksViews
{
    /**
     * Track a view for the model after validating the request.
     *
     * @param Request $request
     * @return bool
     */
    public function trackView(Request $request): bool
    {
        // --- বট প্রতিরোধ এবং ইউনিকনেস চেক ---
        if ($this->isTrackableRequest($request)) {
            $this->incrementViews();
            $this->storeViewedSession();
            return true;
        }
        return false;
    }

    /**
     * Increment the views_count without touching timestamps.
     */
    protected function incrementViews(): void
    {
        $this->forceFill([
            'views_count' => $this->views_count + 1,
        ])->saveQuietly(); // saveQuietly() কোনো ইভেন্ট ফায়ার করে না
    }

    /**
     * Check if the request is valid for tracking a view.
     *
     * @param Request $request
     * @return bool
     */
    protected function isTrackableRequest(Request $request): bool
    {
        // ১. সেশনে আগে থেকেই ভিউ করা আছে কি না চেক করুন
        if (Session::has($this->getViewSessionKey())) {
            return false;
        }

        // ২. বট কি না তা চেক করুন (একটি সহজ পদ্ধতি)
        if ($this->isBot($request->userAgent())) {
            return false;
        }

        // ৩. (ঐচ্ছিক) লগইন করা ইউজার হলে ডেটাবেসে চেক করুন
        if ($request->user()) {
            // আপনি একটি আলাদা 'property_views' টেবিল তৈরি করতে পারেন
            // যেখানে user_id এবং property_id থাকবে, যাতে একজন ইউজার মাত্র একবারই ভিউ কাউন্ট করতে পারে।
//             return ! $this->views()->where('user_id', $request->user()->id)->exists();
        }

        return true;
    }

    /**
     * Store the fact that this model has been viewed in the session.
     */
    protected function storeViewedSession(): void
    {
        Session::put($this->getViewSessionKey(), true);
    }

    /**
     * Get the unique session key for tracking views of this model.
     *
     * @return string
     */
    protected function getViewSessionKey(): string
    {
        return 'viewed_' . strtolower(class_basename($this)) . '_' . $this->getKey();
    }

    /**
     * A simple bot detection based on user agent.
     *
     * @param string|null $userAgent
     * @return bool
     */
    protected function isBot(?string $userAgent): bool
    {
        if (is_null($userAgent)) {
            return true;
        }

        // কিছু সাধারণ বট ইউজার এজেন্টের তালিকা
        $bots = [
            'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider',
            'YandexBot', 'Sogou', 'Exabot', 'facebot', 'ia_archiver',
            'AhrefsBot', 'SemrushBot', 'MJ12bot', 'DotBot', 'crawler'
        ];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
