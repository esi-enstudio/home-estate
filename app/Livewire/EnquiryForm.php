<?php

namespace App\Livewire;

use App\Mail\NewEnquiryMail;
use App\Models\Enquiry;
use App\Models\Property;
use App\Notifications\NewEnquiryNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EnquiryForm extends Component
{
    public Property $property;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $message = '';

    public bool $isSubmitted = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone' => 'required|string|min:10',
            'message' => 'required|string|min:20|max:1000',
        ];
    }

    // লাইভ ভ্যালিডেশনের জন্য
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function mount(Property $property): void
    {
        $this->property = $property;

        // যদি ইউজার লগইন করা থাকে, তাহলে তার তথ্য দিয়ে ফর্মটি পূরণ করা হবে
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
        }

        // একটি আকর্ষণীয় ডিফল্ট মেসেজ
        $this->message = "I'm interested in your property '{$this->property->title}' (Code: {$this->property->property_code}). Please contact me with more details.";
    }

    public function saveEnquiry(): void
    {
        $this->validate();

        $enquiry = Enquiry::create([
            'property_id' => $this->property->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'user_id' => Auth::id(),
        ]);

        // ===== START: নোটিফিকেশন এবং ইমেইল পাঠানোর কোড =====
        try {
            $owner = $this->property->user;

            // ডাটাবেস নোটিফিকেশন পাঠান
            $owner->notify(new NewEnquiryNotification($enquiry));

            // ইমেইল পাঠান
            Mail::to($owner->email)->send(new NewEnquiryMail($enquiry));

        } catch (\Exception $e) {
            // যদি ইমেইল বা নোটিফিকেশন পাঠাতে কোনো সমস্যা হয়,
            // তাহলে সেটি লগ করা হবে কিন্তু ব্যবহারকারী কোনো এরর দেখবে না।
            report($e);
        }
        // ===== END: নোটিফিকেশন এবং ইমেইল পাঠানোর কোড =====

        $this->isSubmitted = true;
    }
    public function render()
    {
        return view('livewire.enquiry-form');
    }
}
