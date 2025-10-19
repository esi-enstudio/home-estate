<?php

namespace App\Livewire;

use App\Models\IdentityVerification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserVerificationForm extends Component
{
    use WithFileUploads; // এই trait টি যোগ করুন

    public $id_type = ''; // ডিফল্ট মান সেট করা ভালো
    public $id_number;
    public $front_image;
    public $back_image;

    // ডাইনামিক লেবেল ও প্লেসহোল্ডারের জন্য প্রোপার্টি
    public string $idNumberLabel = 'আইডি নম্বর';
    public string $idNumberPlaceholder = 'আপনার আইডি কার্ডের নম্বর দিন';
    public string $frontImageLabel = 'আইডি কার্ডের ছবি';

    /**
     * کمپوننٹ 처음 লোড হওয়ার সময় ডিফল্ট মান সেট করার জন্য
     */
    public function mount(): void
    {
        $this->updateDynamicFields($this->id_type);
    }

    /**
     * ভ্যালিডেশন রুলস এখন একটি মেথড, যা id_type এর উপর নির্ভর করে পরিবর্তিত হবে
     */
    protected function rules(): array
    {
        $rules = [
            'id_type' => 'required|string|in:NID,Passport,Driving License,Birth Certificate',
            'id_number' => 'required|string|max:17',
            'front_image' => 'required|image|max:1024',
        ];

        // যদি NID সিলেক্ট করা হয়, তবে back_image বাধ্যতামূলক হবে
        if ($this->id_type === 'NID') {
            $rules['back_image'] = 'required|image|max:1024';
        } else {
            $rules['back_image'] = 'nullable|image|max:1024'; // অন্যান্য ক্ষেত্রে এটি ঐচ্ছিক
        }

        return $rules;
    }

    /**
     * রিয়েল-টাইম ভ্যালিডেশন মেসেজ দেখানোর জন্য।
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * যখনই id_type প্রোপার্টি পরিবর্তন হবে, এই মেথডটি স্বয়ংক্রিয়ভাবে রান হবে
     */
    public function updatedIdType($value): void
    {
        // লেবেল ও প্লেসহোল্ডার আপডেট করা
        $this->updateDynamicFields($value);
        // পুরোনো ভ্যালিডেশন ত্রুটি মুছে ফেলা
        $this->resetErrorBag();
        // ছবি রিসেট করা, যাতে ভুল ছবি সাবমিট না হয়
        $this->reset(['front_image', 'back_image']);
    }

    /**
     * ডাইনামিক ফিল্ডগুলোর টেক্সট আপডেট করার জন্য একটি হেল্পার মেথড
     */
    private function updateDynamicFields(string $type): void
    {
        switch ($type) {
            case 'NID':
                $this->idNumberLabel = 'জাতীয় পরিচয়পত্রের নম্বর';
                $this->idNumberPlaceholder = 'আপনার NID নম্বর দিন';
                $this->frontImageLabel = 'NID কার্ডের সামনের দিকের ছবি';
                break;
            case 'Passport':
                $this->idNumberLabel = 'পাসপোর্ট নম্বর';
                $this->idNumberPlaceholder = 'আপনার পাসপোর্ট নম্বর দিন';
                $this->frontImageLabel = 'পাসপোর্টের ছবি';
                break;
            case 'Driving License':
                $this->idNumberLabel = 'ড্রাইভিং লাইসেন্স নম্বর';
                $this->idNumberPlaceholder = 'আপনার লাইসেন্স নম্বর দিন';
                $this->frontImageLabel = 'লাইসেন্সের ছবি';
                break;
            case 'Birth Certificate':
                $this->idNumberLabel = 'জন্ম সনদ নম্বর';
                $this->idNumberPlaceholder = 'আপনার জন্ম সনদ নম্বর দিন';
                $this->frontImageLabel = 'জন্ম সনদের ছবি';
                break;
            default:
                $this->idNumberLabel = 'আইডি নম্বর';
                $this->idNumberPlaceholder = 'আপনার আইডি কার্ডের নম্বর দিন';
                $this->frontImageLabel = 'আইডি কার্ডের ছবি';
                break;
        }
    }

    public function submitVerificationRequest(): void
    {
        $validatedData = $this->validate();
        $user = Auth::user();

        $userFolder = Str::slug($user->name . '-' . $user->id);
        $uploadPath = 'id-verifications/' . $userFolder;

        $validatedData['front_image'] = $this->front_image->store($uploadPath, 'public');

        // যদি back_image থাকে (শুধুমাত্র NID এর ক্ষেত্রে), তাহলে সেটিও স্টোর করা হবে
        if ($this->back_image) {
            $validatedData['back_image'] = $this->back_image->store($uploadPath, 'public');
        }

        IdentityVerification::create([
            'user_id' => $user->id,
            'id_type' => $validatedData['id_type'],
            'id_number' => $validatedData['id_number'],
            'front_image' => $validatedData['front_image'],
            'back_image' => $validatedData['back_image'] ?? null,
            'status' => 'pending',
        ]);

        session()->flash('status', 'আপনার আবেদনটি সফলভাবে জমা হয়েছে। অনুমোদনের জন্য অপেক্ষা করুন।');
        $this->reset();
        $this->dispatch('form-submitted');
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.user-verification-form');
    }
}
