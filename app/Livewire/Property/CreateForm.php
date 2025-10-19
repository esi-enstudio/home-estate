<?php

namespace App\Livewire\Property;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;

    // স্টেপ ম্যানেজমেন্ট
    public int $currentStep = 1;

    // মডেল প্রোপার্টি (ফর্মের সকল ফিল্ড)
    public $property_type_id;
    public $title, $description, $purpose = 'rent';
    public $rent_price, $rent_type = 'month', $service_charge, $security_deposit, $is_negotiable = 'fixed';
    public $bedrooms, $bathrooms, $balconies, $size_sqft, $floor_level, $total_floors, $facing_direction, $year_built;
    public $address_street, $address_area, $address_zipcode;
    public $video_url, $house_rules;
    public $available_from;
    public $photos = [];

    // হেল্পার প্রোপার্টি
    public $propertyTypes;
    public bool $isResidential = false;

    public function mount(): void
    {
        // এখানে আপনার ডেটাবেজ থেকে ডেটা লোড করুন
        $this->propertyTypes = PropertyType::orderBy('name_en')->get();
        $this->available_from = now()->format('Y-m-d');
    }

    /**
     * সকল ভ্যালিডেশন রুলস এখানে সংজ্ঞায়িত করা হয়েছে।
     */
    protected function rules(): array
    {
        $rules = [
            // ধাপ ১
            'property_type_id' => 'required|exists:property_types,id',
            'purpose' => 'required|in:rent,sell',
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50',
            'available_from' => 'required|date|after_or_equal:today',

            // ধাপ ২
            'size_sqft' => 'required|integer|min:1',
            'floor_level' => 'nullable|string|max:50',
            'total_floors' => 'nullable|integer|min:1',
            'facing_direction' => 'nullable|string',
            'year_built' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),

            // ধাপ ৩
            'rent_price' => 'required|integer|min:0',
            'is_negotiable' => 'required|in:fixed,negotiable',
            'address_area' => 'required|string|max:255',
            'address_street' => 'required|string|max:1000',
            'address_zipcode' => 'nullable|string|max:20',

            // ধাপ ৪
            'photos' => 'required|array|min:1|max:10', // কমপক্ষে ১টি এবং সর্বোচ্চ ১০টি ছবি
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // প্রতিটি ছবির জন্য ভ্যালিডেশন
            'video_url' => 'nullable|url',
            'house_rules' => 'nullable|string|max:2000',
        ];

        // শর্তসাপেক্ষ ভ্যালিডেশন
        if ($this->isResidential) {
            $rules['bedrooms'] = 'required|integer|min:1';
            $rules['bathrooms'] = 'required|integer|min:1';
            $rules['balconies'] = 'nullable|integer|min:0';
        }

        if ($this->purpose === 'rent') {
            $rules['service_charge'] = 'nullable|integer|min:0';
            $rules['security_deposit'] = 'nullable|integer|min:0';
        }

        return $rules;
    }

    /**
     * ভ্যালিডেশন মেসেজগুলো বাংলায় কাস্টমাইজ করা হয়েছে।
     */
    protected function messages(): array
    {
        return [
            'property_type_id.required' => 'প্রোপার্টির ধরন নির্বাচন করা আবশ্যক।',
            'title.required' => 'শিরোনাম দেওয়া আবশ্যক।',
            'title.min' => 'শিরোনাম কমপক্ষে ১০ অক্ষরের হতে হবে।',
            'description.required' => 'বিস্তারিত বর্ণনা দেওয়া আবশ্যক।',
            'description.min' => 'বর্ণনা কমপক্ষে ৫০ অক্ষরের হতে হবে।',
            'available_from.required' => 'কবে থেকে পাওয়া যাবে, সেই তারিখ দিন।',
            'available_from.after_or_equal' => 'তারিখটি আজকের বা ভবিষ্যতের হতে হবে।',
            'size_sqft.required' => 'প্রোপার্টির আকার (স্কয়ার ফিট) দিন।',
            'bedrooms.required' => 'বেডরুমের সংখ্যা দিন।',
            'bathrooms.required' => 'বাথরুমের সংখ্যা দিন।',
            'rent_price.required' => 'ভাড়া বা বিক্রয় মূল্য উল্লেখ করুন।',
            'address_area.required' => 'এলাকার নাম উল্লেখ করুন।',
            'address_street.required' => 'রাস্তার ঠিকানা উল্লেখ করুন।',
            'photos.required' => 'কমপক্ষে একটি ছবি আপলোড করুন।',
            'photos.*.image' => 'আপলোড করা ফাইলটি অবশ্যই একটি ছবি হতে হবে।',
            'photos.*.mimes' => 'ছবি অবশ্যই jpeg, png, jpg, বা webp ফরম্যাটের হতে হবে।',
            'photos.*.max' => 'প্রতিটি ছবির আকার ২ মেগাবাইটের বেশি হতে পারবে না।',
            'video_url.url' => 'সঠিক ভিডিও ইউআরএল দিন।',
        ];
    }

    /**
     * যখনই প্রোপার্টির ধরন পরিবর্তন হবে, এই মেথডটি কাজ করবে।
     */
    public function updatedPropertyTypeId($value): void
    {
        // আপনার ডেটাবেজ অনুযায়ী আবাসিক প্রোপার্টির আইডিগুলো এখানে দিন
        $residentialTypeIds = PropertyType::whereIn('name_en', ['Apartment', 'Duplex', 'Penthouse', 'Studio'])->pluck('id')->toArray();

        $this->isResidential = in_array($value, $residentialTypeIds);

        if (!$this->isResidential) {
            $this->reset(['bedrooms', 'bathrooms', 'balconies', 'facing_direction', 'year_built']);
        }
        $this->resetErrorBag(); // পুরোনো ভ্যালিডেশন ভুল মুছে ফেলা
    }

    /**
     * রিয়েল-টাইম ভ্যালিডেশন
     * @throws ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * প্রতিটি ধাপের জন্য নির্দিষ্ট ফিল্ড ভ্যালিডেট করা।
     */
    public function validateStep(int $step): void
    {
        $fieldsToValidate = [];
        if ($step == 1) {
            $fieldsToValidate = ['property_type_id', 'purpose', 'title', 'description', 'available_from'];
        } elseif ($step == 2) {
            $fieldsToValidate = ['size_sqft', 'floor_level', 'total_floors', 'facing_direction', 'year_built'];
            if ($this->isResidential) {
                $fieldsToValidate = array_merge($fieldsToValidate, ['bedrooms', 'bathrooms', 'balconies']);
            }
        } elseif ($step == 3) {
            $fieldsToValidate = ['rent_price', 'is_negotiable', 'address_area', 'address_street', 'address_zipcode'];
            if ($this->purpose === 'rent') {
                $fieldsToValidate = array_merge($fieldsToValidate, ['service_charge', 'security_deposit']);
            }
        } elseif ($step == 4) {
            $fieldsToValidate = ['photos', 'photos.*', 'video_url', 'house_rules'];
        }

        $this->validate(collect($this->rules())->only($fieldsToValidate)->toArray());
    }

    public function nextStep(): void
    {
        $this->validateStep($this->currentStep);
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * ফাইনাল সাবমিশন।
     */
    public function submitForm(): void
    {
        $validatedData = $this->validate(); // সাবমিটের আগে সম্পূর্ণ ফর্ম ভ্যালিডেট করা
        $user = Auth::user();

        $validatedData['user_id'] = $user->id;
        $validatedData['slug'] = Str::slug($this->title) . '-' . uniqid(); // ইউনিক স্লাগ তৈরি
        $validatedData['property_code'] = 'BHARA-' . (Property::max('id') + 1); // ইউনিক কোড তৈরি

        // শুধুমাত্র আবাসিক প্রোপার্টির জন্য প্রযোজ্য ফিল্ডগুলো সেট করা
        $validatedData['bedrooms'] = $this->isResidential ? $this->bedrooms : null;
        $validatedData['bathrooms'] = $this->isResidential ? $this->bathrooms : null;
        $validatedData['balconies'] = $this->isResidential ? $this->balconies : null;
        $validatedData['facing_direction'] = $this->isResidential ? $this->facing_direction : null;
        $validatedData['year_built'] = $this->isResidential ? $this->year_built : null;

        // ছবি আপলোড এবং ডেটাবেজে পাথ সেভ করার জন্য
        // দ্রষ্টব্য: আপনার একটি `property_images` টেবিল থাকা প্রয়োজন।
        // TODO: নিচের ছবির লজিকটি আপনার `property_images` টেবিলে সেভ করুন।
        $photoPaths = [];
        foreach ($this->photos as $photo) {
            $photoPaths[] = $photo->store('properties', 'public');
        }

        // মূল প্রোপার্টি তৈরি
        $property = Property::create($validatedData);
        // $property->images()->createMany($photoPaths); // উদাহরণ

        session()->flash('success', 'আপনার প্রোপার্টি সফলভাবে জমা হয়েছে এবং পর্যালোচনার অধীনে আছে।');
        // $this->reset(); // ফর্ম রিসেট করা
    }

    public function render()
    {
        return view('livewire.property.create-form');
    }
}
