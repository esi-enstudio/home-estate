<?php

namespace App\Livewire;

use App\Models\Property;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class PopularListings extends Component
{
    public string $purpose = 'rent'; // ডিফল্ট ট্যাব 'rent' থাকবে

    // এই মেথডটি ট্যাবে ক্লিক করলে কল হবে
    public function setPurpose(string $purpose): void
    {
        $this->purpose = $purpose;
    }

    // Computed Property ব্যবহার করে ডাইনামিকভাবে প্রপার্টি লোড করা হচ্ছে
    public function getPropertiesProperty()
    {
        // প্রতিটি ট্যাবের জন্য আলাদা ক্যাশ কী তৈরি করা হচ্ছে
        $cacheKey = 'popular_listings_' . $this->purpose;

        // ৬ ঘণ্টার জন্য ক্যাশ করা হবে
        return Cache::remember($cacheKey, now()->addHours(6), function () {
            return Property::with(['user', 'propertyType', 'media'])
                ->where('status', 'active')
                ->where('purpose', $this->purpose) // ডাইনামিক 'purpose' অনুযায়ী ফিল্টার
                ->orderByDesc('is_featured') // ফিচার্ড প্রপার্টিগুলোকে অগ্রাধিকার দেওয়া
                ->orderByDesc('score')       // তারপর স্কোর অনুযায়ী সাজানো
                ->take(6) // সর্বোচ্চ ৬টি প্রপার্টি দেখানো হবে
                ->get();
        });
    }
    public function render(): Factory|View
    {
        return view('livewire.popular-listings', [
            'properties' => $this->properties, // Computed property এখানে পাস করা হচ্ছে
        ]);
    }
}
