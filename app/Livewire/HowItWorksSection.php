<?php

namespace App\Livewire;

use App\Models\HowItWorksStep;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class HowItWorksSection extends Component
{
    public $steps = [];

    public function mount(): void
    {
        $this->steps = Cache::remember('how_it_works_steps', now()->addHours(6), function () {
            return HowItWorksStep::where('is_active', true)
                ->orderBy('sort_order')
                ->take(3) // ডিজাইনে ৩টি ধাপ আছে
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.how-it-works-section');
    }
}
