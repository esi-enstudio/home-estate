<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ১. ব্যবহারকারী লগইন করা আছে এবং Filament প্যানেল অ্যাক্সেস করছে কিনা তা পরীক্ষা করা
        if (Auth::check() && Filament::isServing()) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                // ২. স্ট্যাটাস 'active' না হলে, Filament থেকে লগআউট করুন
                Filament::auth()->logout();

                // ৩. সেশন ইনভ্যালিডেট করুন
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // ৪. একটি এরর মেসেজসহ লগইন পেজে রিডাইরেক্ট করুন
                return redirect(Filament::getLoginUrl())
                    ->with('error', 'আপনার অ্যাকাউন্টের স্ট্যাটাস পরিবর্তন হওয়ায় আপনাকে লগআউট করা হয়েছে।');
            }
        }

        // ৫. সবকিছু ঠিক থাকলে, রিকোয়েস্টটি চালিয়ে যেতে দিন
        return $next($request);
    }
}
