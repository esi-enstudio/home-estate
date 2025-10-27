<?php

namespace App\Livewire;

use App\Models\PropertyType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class PropertyTypesSection extends Component
{
    public $propertyTypes = [];

    public function mount(): void
    {
        // ৬ ঘণ্টার জন্য ফলাফল ক্যাশ করা হবে
        $this->propertyTypes = Cache::remember('popular_property_types', now()->addHours(6), function () {
            // শুধুমাত্র সেই টাইপগুলো আনা হচ্ছে যেখানে অন্তত একটি প্রপার্টি আছে
            // এবং properties_count অনুযায়ী বড় থেকে ছোট সাজানো হচ্ছে
            return PropertyType::with('media') // N+1 সমস্যা এড়ানোর জন্য মিডিয়া লোড করা হচ্ছে
                ->whereHas('properties')
                ->orderByDesc('properties_count')
                ->take(4) // সেরা ৪টি দেখানো হবে
                ->get();
        });
    }
    public function render(): Factory|View
    {
        return view('livewire.property-types-section');
    }
}
