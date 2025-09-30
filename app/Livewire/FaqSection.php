<?php

namespace App\Livewire;

use App\Models\Faq;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class FaqSection extends Component
{
    public $faqs = [];

    public function mount(): void
    {
        $this->faqs = Cache::remember('homepage_faqs', now()->addHours(6), function () {
            return Faq::where('is_featured', true)
                ->orderBy('sort_order')
                ->take(5) // ডিজাইনে ৫টি আইটেম আছে
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.faq-section');
    }
}
