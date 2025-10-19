<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateInformation extends Component
{
    use WithFileUploads;

    public $name;
    public $bio;
    public $designation;
    public $social_links = ['facebook' => '', 'twitter' => '', 'linkedin' => ''];
    public $avatar; // Temporary file object
    public $existingAvatar; // Existing avatar URL

    public $verificationStatus;
    public bool $isVerified;

    public function mount($verificationStatus): void
    {
        $user = Auth::user();
        $this->verificationStatus = $verificationStatus;
        $this->isVerified = ($this->verificationStatus === 'approved');

        $this->name = $user->name;
        $this->bio = $user->bio;
        $this->designation = $user->designation;
        $this->social_links = array_merge($this->social_links, $user->social_links ?? []);
        $this->existingAvatar = $user->avatar_url;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $rules = [
            'bio' => 'nullable|string|max:500',
            'designation' => 'nullable|string|max:150',
            'social_links.facebook' => 'nullable|url',
            'social_links.twitter' => 'nullable|url',
            'social_links.linkedin' => 'nullable|url',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ];

        // শুধু ভেরিফাইড না হলেই নাম আপডেট করা যাবে
        if (!$this->isVerified) {
            $rules['name'] = 'required|string|max:255';
        }

        $validated = $this->validate($rules);

        $updateData = [
            'bio' => $this->bio,
            'designation' => $this->designation,
            'social_links' => $this->social_links,
        ];

        if (!$this->isVerified) {
            $updateData['name'] = $this->name;
        }

        if ($this->avatar) {
            $updateData['avatar_url'] = $this->avatar->store('avatars', 'public');
        }

        $user->update($updateData);

        session()->flash('status', 'প্রোফাইল সফলভাবে আপডেট হয়েছে।');

        // পেজ রিফ্রেশ করার জন্য, যাতে নতুন ছবি দেখা যায়
        return $this->redirect(route('profile.show'));
    }

    public function render()
    {
        return view('livewire.profile.update-information');
    }
}
