<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = Auth::user();

        // ইউজারের সর্বশেষ ভেরিফিকেশন রিকোয়েস্টটি খুঁজে বের করা হচ্ছে
        $latestVerification = $user->identityVerifications()->latest()->first();

        return view('pages.profile', [
            'user' => $user,
            'verification' => $latestVerification,
        ]);
    }
}
