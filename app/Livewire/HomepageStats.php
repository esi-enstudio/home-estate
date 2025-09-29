<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class HomepageStats extends Component
{
    public int $rentalsCompleted = 0;
    public int $trustedOwners = 0;
    public int $happyClients = 0;
    public int $activeProperties = 0;

    /**
     * কম্পোনেন্ট মাউন্ট হওয়ার সময় ডাটা লোড হবে।
     */
    public function mount(): void
    {
        // ৬ ঘণ্টার জন্য পরিসংখ্যানগুলো ক্যাশ করা হবে
        $stats = Cache::remember('homepage_stats', now()->addHours(6), function () {
            return [
                'rentalsCompleted' => Property::whereIn('status', ['rented', 'sold_out'])->count(),
                'trustedOwners' => User::has('properties')->count(), // শুধুমাত্র যে ইউজারদের প্রপার্টি আছে
                'happyClients' => Review::distinct('user_id')->count(), // স্বতন্ত্র রিভিউ প্রদানকারী
                'activeProperties' => Property::where('status', 'active')->count(),
            ];
        });

        $this->rentalsCompleted = $stats['rentalsCompleted'];
        $this->trustedOwners = $stats['trustedOwners'];
        $this->happyClients = $stats['happyClients'];
        $this->activeProperties = $stats['activeProperties'];
    }

    public function render(): Factory|View
    {
        return view('livewire.homepage-stats');
    }
}
