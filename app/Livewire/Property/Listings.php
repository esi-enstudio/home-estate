<?php

namespace App\Livewire\Property;

use App\Models\Property;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Listings extends Component
{
    use WithPagination;

    /**
     * @throws AuthorizationException
     */
    public function delete($propertyId): void
    {
        $property = Property::findOrFail($propertyId);
        $this->authorize('delete', $property); // পলিসি দিয়ে অথোরাইজেশন চেক

        $property->delete(); // Spatie media library স্বয়ংক্রিয়ভাবে মিডিয়া মুছে ফেলবে

        session()->flash('success', 'প্রোপার্টি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function render(): Factory|View
    {
        $properties = Auth::user()
            ->properties() // User মডেলে properties() রিলেশনশিপ থাকতে হবে
            ->latest()
            ->paginate(10);

        return view('livewire.property.listings', [
            'properties' => $properties,
        ]);
    }
}
