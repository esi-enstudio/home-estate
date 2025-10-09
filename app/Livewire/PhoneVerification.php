<?php

namespace App\Livewire;

use Livewire\Component;
use Twilio\Rest\Client;

/**
 * @method static where(string $string, $id)
 * @method static create(array $array)
 */
class PhoneVerification extends Component
{
    public User $user;
    public bool $otpSent = false;
    public string $otp = '';

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    /**
     * OTP তৈরি করে এবং SMS-এর মাধ্যমে পাঠায়।
     */
    public function sendOtp(): void
    {
        // পুরোনো OTP ডিলিট করে দিন
        PhoneVerification::where('user_id', $this->user->id)->delete();

        // একটি ৬-সংখ্যার OTP তৈরি করুন
        $otpCode = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(5); // OTP ৫ মিনিটের জন্য বৈধ থাকবে

        // ডাটাবেজে OTP সেভ করুন
        PhoneVerification::create([
            'user_id' => $this->user->id,
            'otp' => $otpCode,
            'expires_at' => $expiresAt,
        ]);

        // SMS পাঠানোর চেষ্টা করুন
        try {
            $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $client->messages->create($this->user->phone, [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => "Your verification code is: {$otpCode}",
            ]);

            $this->otpSent = true;
            session()->flash('success', 'আপনার ফোনে একটি OTP কোড পাঠানো হয়েছে।');
        } catch (\Exception $e) {
            session()->flash('error', 'দুঃখিত, SMS পাঠাতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        }
    }

    /**
     * ব্যবহারকারীর দেওয়া OTP ভেরিফাই করে।
     */
    public function verifyOtp(): void
    {
        $this->validate(['otp' => 'required|digits:6']);

        $verification = PhoneVerification::where('user_id', $this->user->id)
            ->where('otp', $this->otp)
            ->where('expires_at', '>', now())
            ->first();

        if ($verification) {
            // ভেরিফিকেশন সফল
            $this->user->update(['phone_verified_at' => now()]);
            $verification->delete(); // ব্যবহৃত OTP ডিলিট করুন

            session()->flash('success', 'আপনার ফোন নম্বর সফলভাবে ভেরিফাই করা হয়েছে!');
            $this->reset('otp', 'otpSent'); // ফর্ম রিসেট করুন
        } else {
            // ভেরিফিকেশন ব্যর্থ
            $this->addError('otp', 'আপনার দেওয়া কোডটি সঠিক নয় বা এর মেয়াদ শেষ হয়ে গেছে।');
        }
    }

    public function render()
    {
        return view('livewire.phone-verification');
    }
}
