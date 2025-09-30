<?php

namespace App\Livewire;

use App\Mail\NewContactMessageMail;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = '';
    public string $message = '';
    public bool $isSubmitted = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'subject' => 'required|min:5',
            'message' => 'required|min:20',
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(): void
    {
        $this->validate();

        $message = Message::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        // --- START: নোটিফিকেশন এবং ইমেইল পাঠানোর কোড ---
        try {
            // অ্যাডমিনদের খুঁজে বের করুন (যেমন: যাদের Super Admin রোল আছে)
            // সহজ উপায়: $admins = User::where('id', 1)->get();
            $admins = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->get();
            $adminEmail = config('mail.admin_address', 'admin@example.com'); // একটি ডিফল্ট ইমেইল

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewContactMessage($message));
            }

            Mail::to($adminEmail)->send(new NewContactMessageMail($message));

        } catch (\Exception $e) {
            report($e);
        }
        // --- END: নোটিফিকেশন এবং ইমেইল পাঠানোর কোড ---

        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
