<?php
// app/Helpers/resolve_url.php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

if (!function_exists('App\Helpers\resolve_url')) {
    function resolve_url(string $url): string
    {
        // চেক করুন URL-টি 'route(' দিয়ে শুরু হয়েছে কিনা
        if (str_starts_with($url, 'route(') && str_ends_with($url, ')')) {
            try {
                // 'route(' এবং ')' অংশটুকু বাদ দিয়ে ভেতরের অংশটুকু বের করা হচ্ছে
                $routeExpression = substr($url, 6, -1);

                // eval() ব্যবহার করা ঝুঁকিপূর্ণ, তাই আমরা এটিকে নিরাপদে পার্স করব
                // উদাহরণ: 'page.show', $page->slug
                $parts = explode(',', $routeExpression);
                $routeName = trim($parts[0], " '\"");

                // আপাতত আমরা শুধুমাত্র প্যারামিটার ছাড়া রাউট সমর্থন করব
                if (Route::has($routeName)) {
                    return route($routeName);
                }
            } catch (\Exception $e) {
                // যদি কোনো কারণে রাউট তৈরি করতে সমস্যা হয়, তাহলে মূল URL-টিই ফেরত পাঠানো হবে
                return $url;
            }
        }

        // যদি এটি একটি সাধারণ URL হয়, তাহলে সেটিই ফেরত পাঠানো হবে
        return $url;
    }
}
