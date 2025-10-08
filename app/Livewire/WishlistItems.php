<?php

namespace App\Livewire;

use App\Models\PropertyType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class WishlistItems extends Component
{
    use WithPagination;

    public ?int $selectedPropertyTypeId = null;

    /**
     * Livewire ইভেন্ট শোনার জন্য।
     * '$refresh' এর পরিবর্তে একটি ডেডিকেটেড মেথড কল করা হবে।
     */
    protected $listeners = ['wishlistUpdated' => 'handleWishlistUpdate'];

    /**
     * এই মেথডটি 'wishlistUpdated' ইভেন্ট এলে কাজ করবে।
     * এর একমাত্র কাজ হলো পেজিনেশন রিসেট করা।
     */
    public function handleWishlistUpdate(): void
    {
        // পেজিনেশনকে প্রথম পাতায় রিসেট করা হচ্ছে
        $this->resetPage();

        // resetPage() কল করার পর, Livewire স্বয়ংক্রিয়ভাবে render() মেথডটি আবার চালাবে।
        // তাই এখানে $refresh() বা অন্য কিছু কল করার প্রয়োজন নেই।
    }

    public function filterByType(?int $typeId): void
    {
        $this->selectedPropertyTypeId = $typeId;
        $this->resetPage(); // ফিল্টার পরিবর্তন হলে পেজিনেশন রিসেট হবে
    }

    public function render(): Factory|View
    {
        $user = Auth::user();

        // ব্যবহারকারীর ফেভারিট করা প্রপার্টিগুলোর কোয়েরি
        $propertiesQuery = $user->favoriteProperties()->with('user', 'propertyType', 'media');

        // যদি কোনো নির্দিষ্ট টাইপ সিলেক্ট করা থাকে, তাহলে ফিল্টার করুন
        if ($this->selectedPropertyTypeId) {
            $propertiesQuery->where('property_type_id', $this->selectedPropertyTypeId);
        }

        // ট্যাবের জন্য: শুধুমাত্র সেই প্রপার্টি টাইপগুলো আনা হচ্ছে যেগুলো উইশলিস্টে আছে
        $favoritedTypeIds = $user->favoriteProperties()->pluck('property_type_id')->unique();
        $propertyTypes = PropertyType::whereIn('id', $favoritedTypeIds)->get();

        return view('livewire.wishlist-items', [
            'properties' => $propertiesQuery->paginate(6),
            'propertyTypes' => $propertyTypes,
        ]);
    }
}
