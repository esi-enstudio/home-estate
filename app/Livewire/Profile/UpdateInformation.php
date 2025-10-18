<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateInformation extends Component
{
    public string $name = '';
    public string $email = '';
    // প্রয়োজনে অন্য ফিল্ড (যেমন: ফোন) যোগ করতে পারেন
    // public string $phone = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        // সফলভাবে আপডেট হওয়ার পর বার্তা দেখানোর জন্য
        session()->flash('status', 'প্রোফাইল সফলভাবে আপডেট হয়েছে।');
    }
    public function render()
    {
        return view('livewire.profile.update-information');
    }
}
