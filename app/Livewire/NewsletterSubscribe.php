<?php

namespace App\Livewire;

use App\Mail\WelcomeSubscriberMail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public string $title;
    public string $subtitle;

    public string $email = '';
    public bool $isSubscribed = false;

    /**
     * কম্পোনেন্ট মাউন্ট হওয়ার সময় বাইরে থেকে পাঠানো ডেটা গ্রহণ করা হবে।
     * প্যারামিটারগুলোকে এখন nullable এবং optional করা হয়েছে।
     */
    public function mount(?string $title = null, ?string $subtitle = null): void
    {
        // === START: মূল এবং চূড়ান্ত সমাধান এখানে ===
        // যদি বাইরে থেকে কোনো title পাস করা না হয়, তাহলে একটি ডিফল্ট মান ব্যবহার করা হবে।
        $this->title = $title ?? 'আমাদের নিউজলেটারে সাবস্ক্রাইব করুন';

        // যদি বাইরে থেকে কোনো subtitle পাস করা না হয়, তাহলে একটি ডিফল্ট মান ব্যবহার করা হবে।
        $this->subtitle = $subtitle ?? 'সাইন আপ করুন এবং আমরা আপনাকে ইমেইলের মাধ্যমে নোটিফিকেশন পাঠাব।';
        // === END ===
    }

    /**
     * ভ্যালিডেশনের নিয়মাবলী।
     */
    protected function rules(): array
    {
        return [
            // 'subscribers' টেবিলের 'email' কলামে ইউনিক হতে হবে
            'email' => 'required|email|unique:subscribers,email',
        ];
    }

    /**
     * কাস্টম ভ্যালিডেশন মেসেজ (বাংলায়)।
     */
    protected function messages(): array
    {
        return [
            'email.required' => 'অনুগ্রহ করে আপনার ইমেইল ঠিকানা দিন।',
            'email.email' => 'অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা দিন।',
            'email.unique' => 'এই ইমেইল ঠিকানাটি ইতোমধ্যে সাবস্ক্রাইব করা আছে। ধন্যবাদ!',
        ];
    }

    /**
     * লাইভ ভ্যালিডেশনের জন্য।
     * ব্যবহারকারী যখনই ইনপুট ফিল্ড থেকে বাইরে ক্লিক করবে, এটি কাজ করবে।
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * ফর্ম সাবমিট হলে এই মেথডটি কাজ করবে।
     */
    public function subscribe(): void
    {
        $validatedData = $this->validate();

        // প্রথমে সাবস্ক্রাইবার তৈরি করুন এবং মডেল ইনস্ট্যান্সটি একটি ভ্যারিয়েবলে রাখুন
        $subscriber = Subscriber::create($validatedData);

        // --- START: স্বাগতম ইমেইল পাঠানোর কোড ---
        try {
            Mail::to($subscriber->email)->send(new WelcomeSubscriberMail($subscriber));
        } catch (\Exception $e) {
            // যদি ইমেইল পাঠাতে সমস্যা হয়, সেটি লগ করা হবে কিন্তু ব্যবহারকারী কোনো এরর দেখবে না
            Log::error('Newsletter subscription welcome email failed to send for: ' . $subscriber->email . ' | Error: ' . $e->getMessage());
        }
        // --- END: স্বাগতম ইমেইল পাঠানোর কোড ---

        $this->isSubscribed = true;
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
