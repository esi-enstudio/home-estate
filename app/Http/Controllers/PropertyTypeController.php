<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of all property types.
     */
    public function index(): Factory|View|\Illuminate\View\View
    {
        // ৬ ঘণ্টার জন্য ফলাফল ক্যাশ করা হবে
        $propertyTypes = Cache::remember('all_property_types', now()->addHours(6), function () {
            // শুধুমাত্র সেই টাইপগুলো আনা হচ্ছে যেখানে অন্তত একটি প্রপার্টি আছে
            // এবং properties_count অনুযায়ী বড় থেকে ছোট সাজানো হচ্ছে
            return PropertyType::with('media') // আইকন/ছবি লোড করার জন্য
            ->whereHas('properties')
                ->orderByDesc('properties_count')
                ->get();
        });

        // ভিউ ফাইলে ডেটা পাস করা হচ্ছে
        return view('pages.property-types.index', [
            'propertyTypes' => $propertyTypes,
        ]);
    }
}
