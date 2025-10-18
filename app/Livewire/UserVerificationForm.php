<?php

namespace App\Livewire;

use App\Models\IdentityVerification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserVerificationForm extends Component
{
    use WithFileUploads; // এই trait টি যোগ করুন

    // ফর্ম ফিল্ডের জন্য পাবলিক প্রোপার্টি
    public $id_type;
    public $id_number;
    public $front_image;
    public $back_image;

    // ভ্যালিডেশন রুলস
    protected $rules = [
        'id_type' => 'required|string|in:NID,Passport,Driving License',
        'id_number' => 'required|string|max:255',
        'front_image' => 'required|image|max:2048', // 2MB max size
        'back_image' => 'nullable|image|max:2048', // 2MB max size
    ];

    /**
     * রিয়েল-টাইম ভ্যালিডেশন মেসেজ দেখানোর জন্য।
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * ফর্ম সাবমিট করার পর এই মেথডটি কাজ করবে।
     */
    public function submitVerificationRequest(): void
    {
        // ডেটা ভ্যালিডেট করুন
        $validatedData = $this->validate();

        // ফাইলগুলো স্টোর করুন
        $validatedData['front_image'] = $this->front_image->store('id-verifications', 'public');

        if ($this->back_image) {
            $validatedData['back_image'] = $this->back_image->store('id-verifications', 'public');
        }

        // ডেটাবেজে নতুন ভেরিফিকেশন রেকর্ড তৈরি করুন
        IdentityVerification::create([
            'user_id' => Auth::id(),
            'id_type' => $validatedData['id_type'],
            'id_number' => $validatedData['id_number'],
            'front_image' => $validatedData['front_image'],
            'back_image' => $validatedData['back_image'] ?? null,
            'status' => 'pending',
        ]);

        // সফলভাবে সাবমিট হওয়ার পর একটি বার্তা দেখান
        session()->flash('status', 'আপনার আবেদনটি সফলভাবে জমা হয়েছে। অনুমোদনের জন্য অপেক্ষা করুন।');

        // ফর্মটি রিসেট করুন
        $this->reset(['id_type', 'id_number', 'front_image', 'back_image']);

        // পেজটি রিফ্রেশ করার জন্য একটি ইভেন্ট পাঠান (ঐচ্ছিক, তবে ভালো)
        $this->dispatch('form-submitted');
    }

    public function render()
    {
        return view('livewire.user-verification-form');
    }
}
