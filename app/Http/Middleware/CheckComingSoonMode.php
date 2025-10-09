<?php

namespace App\Http\Middleware;


use App\Settings\MaintenanceSettings;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckComingSoonMode
{
    public function __construct(protected MaintenanceSettings $settings) {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ধাপ ১: চেক করুন "Coming Soon" মোডটি অ্যাডমিন প্যানেল থেকে চালু করা আছে কিনা
        if (!$this->settings->coming_soon_enabled) {
            return $next($request); // যদি মোডটি বন্ধ থাকে, তাহলে স্বাভাবিকভাবে এগিয়ে যান
        }

        // === START: মূল এবং চূড়ান্ত সমাধান এখানে ===
        // ধাপ ২: চেক করুন লঞ্চের তারিখ সেট করা আছে কিনা এবং সেই সময়টি কি এখনো আসেনি
        if ($this->settings->launch_date) {
            // Carbon ব্যবহার করে launch_date-টিকে একটি ডেট অবজেক্টে রূপান্তরিত করা হচ্ছে
            $launchDate = Carbon::parse($this->settings->launch_date);

            // যদি বর্তমান সময় লঞ্চের সময়ের চেয়ে বেশি বা সমান হয় (অর্থাৎ সময় শেষ)
            if (Carbon::now()->gte($launchDate)) {
                // আপনি চাইলে এখানে স্বয়ংক্রিয়ভাবে coming_soon_enabled ফ্ল্যাগটি false করে দিতে পারেন
                // $this->settings->coming_soon_enabled = false;
                // $this->settings->save();

                return $next($request); // সাইটটিকে লাইভ করে দিন
            }
        }
        // === END ===

        // ধাপ ৩: ব্যতিক্রমগুলো (Exceptions) চেক করুন
        // সুপার-অ্যাডমিন প্যানেল এবং এর লগইন পেজকে সর্বদা অনুমতি দিন
        if ($request->is('superadmin') || $request->is('superadmin/*')) {
            return $next($request);
        }

        // "Coming Soon" পেজটিকেই অনুমতি দিন, নাহলে redirect loop তৈরি হবে
        if ($request->is('coming-soon')) {
            return $next($request);
        }

        // Livewire-এর রিকোয়েস্টগুলোকে অনুমতি দিন
        if ($request->is('livewire/*')) {
            return $next($request);
        }

        // যদি কোনো সুপার-অ্যাডমিন লগইন করা থাকে, তাহলে তাকে সম্পূর্ণ সাইট ব্রাউজ করার অনুমতি দিন
        if (Auth::check() && Auth::user()->hasRole('super_admin')) {
            return $next($request);
        }

        // ধাপ ৪: যদি উপরের কোনো ব্যতিক্রম না মেলে, তাহলে "Coming Soon" পেজে redirect করুন
        return redirect()->route('coming-soon');
    }
}
