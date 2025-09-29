<?php

namespace App\Livewire;

use App\Models\FeaturedLocation;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class FeaturedLocationsSection extends Component
{
    public $locations = [];

    public function mount(): void
    {
        // ৬ ঘণ্টার জন্য ফলাফল ক্যাশ করা হবে
        $this->locations = Cache::remember('featured_locations', now()->addHours(6), function () {
            return FeaturedLocation::with('media')
                // 'withCount' ব্যবহার করে প্রতিটি এলাকার জন্য সক্রিয় প্রপার্টির সংখ্যা গণনা করা হচ্ছে
                ->withCount(['properties' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->take(4) // ডিজাইনে ৪টি আইটেম আছে
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.featured-locations-section');
    }
}
