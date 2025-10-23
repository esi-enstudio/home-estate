<?php

namespace App\Http\Controllers;

use App\Settings\ContactSettings;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ContactSettings $settings): Factory|View|\Illuminate\View\View
    {
        // $settings অবজেক্টটি এখন সব ডেটা সহ ভিউতে সহজলভ্য হবে
        return view('pages.contact', ['settings' => $settings]);
    }
}
