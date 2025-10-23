<?php

namespace App\Livewire\Listings;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * পেজিনেশনের জন্য Bootstrap 5 থিম ব্যবহার করা হচ্ছে।
     */
    protected $paginationTheme = 'bootstrap';

    // স্ট্যাটাস পরিবর্তনের জন্য সম্ভাব্য অপশনগুলো
    public array $statusOptions = ['active', 'rented'];

    public string $search = ''; // সার্চ ইনপুটের মান এখানে থাকবে

    /**
     * সার্চ ইনপুট পরিবর্তন হলে পেজিনেশন রিসেট করার জন্য।
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * কোনো একটি প্রোপার্টির স্ট্যাটাস আপডেট করার মেথড।
     *
     * @param int $propertyId
     * @param string $status
     * @return void
     */
    public function updateStatus(int $propertyId, string $status): void
    {
        // প্রথমে প্রোপার্টিটি খুঁজে বের করা
        $property = Property::find($propertyId);

        // পলিসি দিয়ে চেক করা হচ্ছে যে এই ইউজার প্রোপার্টিটি আপডেট করতে পারবে কি না
        if (Auth::user()->can('update', $property)) {
            // স্ট্যাটাসটি বৈধ কি না তা নিশ্চিত করা
            if (in_array($status, $this->statusOptions)) {
                $property->status = $status;
                $property->save();

                // সফলভাবে আপডেট হওয়ার পর একটি বার্তা দেখানো
                session()->flash('success', '"' . $property->title . '" এর স্ট্যাটাস সফলভাবে আপডেট হয়েছে।');
            }
        }
    }

    public function render()
    {
        // --- render মেথডের কোয়েরি আপডেট করা হয়েছে ---
        $query = Auth::user()->properties()
            ->with(['propertyType', 'media']);

        // যদি সার্চ ইনপুটে কোনো মান থাকে
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('property_code', 'like', '%' . $this->search . '%');
            });
        }

        $properties = $query->latest()->paginate(5);

        return view('livewire.listings.index', [
            'properties' => $properties,
        ]);
    }
}
