<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     * এই পেজটি Livewire কম্পোনেন্টকে রেন্ডার করবে।
     */
    public function index()
    {
        // এখন আর ডেটা লোড করার কোনো প্রয়োজন নেই।
        // শুধুমাত্র মূল ভিউ ফাইলটি রিটার্ন করা হচ্ছে।
        return view('properties.index');
    }

    /**
     * একটি নির্দিষ্ট প্রপার্টির বিস্তারিত পেজ দেখায়।
     *
     * @param Property $property (Route Model Binding এর মাধ্যমে স্বয়ংক্রিয়ভাবে আসবে)
     * @return View
     */
    public function show(Property $property)
    {
        // ধাপ ১: নিরাপত্তা চেক - শুধুমাত্র 'active' প্রপার্টিই পাবলিকলি দেখা যাবে।
        // আপনি চাইলে Policy ব্যবহার করতে পারেন: $this->authorize('view', $property);
        if ($property->status !== 'active' && !(auth()->check() && auth()->user()->can('view_any_property'))) {
            abort(404);
        }

        // ধাপ ২: শুধুমাত্র প্রয়োজনীয় এবং অনুমোদিত ডেটা Eager Load করা
        $property->load([
            'user',
            'propertyType',
            'media',
            'amenities',
            // শুধুমাত্র অনুমোদিত রিভিউ এবং তাদের ইউজার লোড করা হচ্ছে
            'reviews' => function ($query) {
                $query->where('status', 'approved')->with('user');
            },
            'division',
            'district',
            'upazila'
        ]);

        // একই এলাকার এবং একই ধরণের অন্যান্য প্রপার্টিগুলো "Similar Properties" হিসেবে দেখানোর জন্য
        $similarProperties = Property::where('status', 'active')
            ->where('id', '!=', $property->id) // বর্তমান প্রপার্টি বাদে
            ->where('property_type_id', $property->property_type_id) // একই ধরণের
            ->where('district_id', $property->district_id) // একই জেলায়
            ->with('media', 'propertyType') // এদেরও কিছু বেসিক তথ্য লোড করা হলো
            ->inRandomOrder()
            ->take(4) // ৪টি সিমিলার প্রপার্টি দেখানো হবে
            ->get();

        // ভিউ ফাইলে $property এবং $similarProperties ভ্যারিয়েবল দুটি পাঠানো হচ্ছে
        return view('properties.show', compact('property', 'similarProperties'));
    }

    /**
     * Increment the view count for a property.
     * Uses session to prevent multiple counts from the same user in a single session.
     *
     * @param  Property  $property
     * @return JsonResponse
     */
    public function incrementViewCount(Property $property): JsonResponse
    {
        // একটি সেশন কী (session key) তৈরি করা হচ্ছে যা এই প্রপার্টির জন্য ইউনিক
        $sessionKey = 'viewed_property_' . $property->id;

        // যদি এই সেশনে এই প্রপার্টিটি আগে ভিউ করা না হয়ে থাকে
        if (!session()->has($sessionKey)) {
            // views_count এক বৃদ্ধি করা হচ্ছে
            $property->increment('views_count');

            // সেশনে একটি ফ্ল্যাগ সেট করা হচ্ছে যাতে একই সেশনে আর কাউন্ট না বাড়ে
            session()->put($sessionKey, true);
        }

        // ফ্রন্টএন্ডকে জানানোর জন্য একটি সফল বার্তা পাঠানো হচ্ছে
        return response()->json(['status' => 'success']);
    }
}
