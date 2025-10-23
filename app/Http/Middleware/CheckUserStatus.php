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
        // ১. ফিলামেন্ট প্যানেল কি অ্যাক্টিভ এবং ইউজার কি লগইন করা আছে তা পরীক্ষা করা
        // Filament::auth()->check() সঠিক গার্ডটি নিজে থেকেই ব্যবহার করে
        if (Filament::isServing() && Filament::auth()->check()) {

            // ২. সঠিক গার্ড থেকে ইউজার অবজেক্টটি নিন
            $user = Filament::auth()->user();

            // ৩. ইউজারের স্ট্যাটাস 'active' না হলে লগআউট করুন
            // আপনার User মডেলে অবশ্যই 'status' কলাম থাকতে হবে
            if ($user && $user->status !== 'active') {

                // ৪. ফিলামেন্টের নিজস্ব মেথড ব্যবহার করে লগআউট করুন
                Filament::auth()->logout();

                // ৫. সেশন ইনভ্যালিডেট করুন
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // ৬. একটি এরর মেসেজসহ ফিলামেন্টের সঠিক লগইন পেজে রিডাইরেক্ট করুন
                return redirect()->to(Filament::getLoginUrl())
                    ->with('error', 'আপনার অ্যাকাউন্টের স্ট্যাটাস পরিবর্তন হওয়ায় আপনাকে লগআউট করা হয়েছে।');
            }
        }

        // ৭. সবকিছু ঠিক থাকলে, রিকোয়েস্টটি চালিয়ে যেতে দিন
        return $next($request);
    }
}
