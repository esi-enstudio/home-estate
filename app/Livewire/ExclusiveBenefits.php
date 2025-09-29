<?php

namespace App\Livewire;

use App\Models\Benefit;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ExclusiveBenefits extends Component
{
    public $benefits = [];

    public function mount(): void
    {
        // ৬ ঘণ্টার জন্য ফলাফল ক্যাশ করা হবে
        $this->benefits = Cache::remember('exclusive_benefits', now()->addHours(6), function () {
            return Benefit::where('is_active', true)
                ->orderBy('sort_order')
                ->take(6) // ডিজাইনে ৬টি আইটেম আছে
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.exclusive-benefits');
    }
}
