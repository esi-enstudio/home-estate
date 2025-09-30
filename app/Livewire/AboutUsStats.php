<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AboutUsStats extends Component
{
    public int $listingsAdded = 0;
    public int $agentsListed = 0;
    public int $salesCompleted = 0;
    public int $usersJoined = 0;

    public function mount(): void
    {
        $stats = Cache::remember('about_us_stats', now()->addHours(6), function () {
            return [
                'listingsAdded' => Property::count(),
                'agentsListed' => User::has('properties')->count(),
                'salesCompleted' => Property::whereIn('status', ['rented', 'sold_out'])->count(),
                'usersJoined' => User::count(),
            ];
        });

        $this->listingsAdded = $stats['listingsAdded'];
        $this->agentsListed = $stats['agentsListed'];
        $this->salesCompleted = $stats['salesCompleted'];
        $this->usersJoined = $stats['usersJoined'];
    }

    public function render()
    {
        return view('livewire.about-us-stats');
    }
}
