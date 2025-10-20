<?php

namespace App\Livewire\Property;

use App\Models\District;
use App\Models\Division;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Contracts\View\Factory;
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

    // --- ফর্মের সব ফিল্ড ---
    // ধাপ ১: মৌলিক তথ্য
    public $property_type_id;
    public $purpose = 'rent';
    public $title;
    public $description;
    public $available_from;

    // ধাপ ২: স্পেসিফিকেশন
    public $size_sqft;
    public $bedrooms;
    public $bathrooms;
    public $balconies;
    public $facing_direction;
    public $year_built;
    public $floor_level;
    public $total_floors;

    // ধাপ ৩: মূল্য ও ঠিকানা
    public $rent_price;
    public $service_charge;
    public $security_deposit;
    public $is_negotiable = 'fixed';
    public $division_id;
    public $district_id;
    public $upazila_id;
    public $union_id;
    public $address_area;
    public $address_zipcode;
    public $address_street;

    // ধাপ ৪: মিডিয়া ও অন্যান্য
    public $thumbnail;
    public $gallery_photos = [];
    public $video_url;
    public $house_rules;

    // --- হেল্পার প্রোপার্টি ---
    public bool $isResidential = false;
    public $propertyTypes = [];
    public $divisions = [];
    public $districts = [];
    public $upazilas = [];
    public $unions = [];

    /**
     * কম্পোনেন্ট মাউন্ট হওয়ার সময় প্রাথমিক ডেটা লোড করা।
     */
    public function mount(): void
    {
        $this->propertyTypes = PropertyType::orderBy('name_bn')->get();
        $this->divisions = Division::orderBy('bn_name')->get();
        $this->available_from = now()->format('Y-m-d');
    }

    /**
     * ভ্যালিডেশন রুলস।
     */
    protected function rules(): array
    {
        $rules = [
            'property_type_id' => 'required|exists:property_types,id',
            'purpose' => 'required|in:rent,sell',
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50',
            'available_from' => 'required|date|after_or_equal:today',
            'size_sqft' => 'required|integer|min:1',
            'floor_level' => 'nullable|string|max:50',
            'total_floors' => 'nullable|integer|min:1',
            'rent_price' => 'required|integer|min:0',
            'is_negotiable' => 'required|in:fixed,negotiable',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'address_area' => 'required|string|max:255',
            'address_street' => 'required|string|max:1000',
            'address_zipcode' => 'nullable|string|max:20',
            'thumbnail' => 'required|image|max:2048',
            'gallery_photos' => 'required|array|min:2|max:10',
            'gallery_photos.*' => 'image|max:2048',
            'video_url' => 'nullable|url',
            'house_rules' => 'nullable|string|max:2000',
        ];

        if ($this->isResidential) {
            $rules['bedrooms'] = 'required|integer|min:1';
            $rules['bathrooms'] = 'required|integer|min:1';
            $rules['balconies'] = 'nullable|integer|min:0';
            $rules['facing_direction'] = 'nullable|string';
            $rules['year_built'] = 'nullable|digits:4|integer|min:1900|max:' . date('Y');
        }

        if ($this->purpose === 'rent') {
            $rules['service_charge'] = 'nullable|integer|min:0';
            $rules['security_deposit'] = 'nullable|integer|min:0';
        }

        return $rules;
    }

    /**
     * বাংলায় কাস্টম ভ্যালিডেশন মেসেজ।
     */
    protected function messages(): array
    {
        return [
            'property_type_id.required' => 'প্রোপার্টির ধরন নির্বাচন করুন।',
            'title.required' => 'শিরোনাম দিন।',
            'description.required' => 'বর্ণনা দিন।',
            'available_from.required' => 'তারিখ দিন।',
            'size_sqft.required' => 'আকার উল্লেখ করুন।',
            'bedrooms.required' => 'বেডরুমের সংখ্যা দিন।',
            'bathrooms.required' => 'বাথরুমের সংখ্যা দিন।',
            'rent_price.required' => 'মূল্য উল্লেখ করুন।',
            'division_id.required' => 'বিভাগ নির্বাচন করুন।',
            'district_id.required' => 'জেলা নির্বাচন করুন।',
            'upazila_id.required' => 'উপজেলা নির্বাচন করুন।',
            'address_area.required' => 'এলাকার নাম দিন।',
            'address_street.required' => 'রাস্তার ঠিকানা দিন।',
            'thumbnail.required' => 'একটি প্রধান ছবি দিন।',
            'gallery_photos.required' => 'কমপক্ষে দুইটি গ্যালারি ছবি দিন।',
            'gallery_photos.min' => 'গ্যালারির জন্য কমপক্ষে দুইটি ছবি দিন।',
        ];
    }

    /**
     * প্রতিটি ধাপের জন্য ভ্যালিডেশন।
     */
    public function validateStep(int $step): void
    {
        $fields = [];
        if ($step == 1) $fields = ['property_type_id', 'purpose', 'title', 'description', 'available_from'];
        if ($step == 2) $fields = ['size_sqft', 'bedrooms', 'bathrooms', 'balconies', 'facing_direction', 'year_built', 'floor_level', 'total_floors'];
        if ($step == 3) $fields = ['rent_price', 'service_charge', 'security_deposit', 'is_negotiable', 'division_id', 'district_id', 'upazila_id', 'union_id', 'address_area', 'address_zipcode', 'address_street'];
        if ($step == 4) $fields = ['thumbnail', 'gallery_photos', 'gallery_photos.*', 'video_url', 'house_rules'];

        $this->validate(collect($this->rules())->only($fields)->toArray());
    }

    // --- ডাইনামিক ফিল্ডের জন্য Updated Hooks ---

    /**
     * @throws ValidationException
     */
    public function updated($propertyName): void
    { $this->validateOnly($propertyName); }

    public function updatedPropertyTypeId($value)
    {
        $residentialTypeIds = PropertyType::whereIn('name_bn', ['Apartment', 'Duplex', 'Penthouse', 'Studio'])->pluck('id')->toArray();
        $this->isResidential = in_array($value, $residentialTypeIds);
        if (!$this->isResidential) $this->reset(['bedrooms', 'bathrooms', 'balconies', 'facing_direction', 'year_built']);
        $this->resetErrorBag();
    }
    public function updatedDivisionId($value): void
    {
        $this->districts = District::where('division_id', $value)->get();
        $this->reset(['district_id', 'upazila_id', 'union_id', 'upazilas', 'unions']);
    }
    public function updatedDistrictId($value): void
    {
        $this->upazilas = Upazila::where('district_id', $value)->get();
        $this->reset(['upazila_id', 'union_id', 'unions']);
    }
    public function updatedUpazilaId($value): void
    {
        $this->unions = Union::where('upazila_id', $value)->get();
        $this->reset('union_id');
    }

    // --- স্টেপ নেভিগেশন ---

    public function nextStep(): void
    {
        $this->validateStep($this->currentStep);
        if ($this->currentStep < 4) $this->currentStep++;
    }
    public function previousStep(): void
    {
        if ($this->currentStep > 1) $this->currentStep--;
    }

    /**
     * ফাইনাল সাবমিশন।
     */
    public function submitForm(): void
    {
        $this->validate(); // সাবমিটের আগে সম্পূর্ণ ফর্ম ভ্যালিডেট করা

        $propertyData = collect($this->validate())->except(['thumbnail', 'gallery_photos'])->toArray();

        $propertyData['user_id'] = Auth::id();
        $propertyData['slug'] = Str::slug($this->title) . '-' . uniqid();
        $propertyData['property_code'] = 'BHA-' . (Property::max('id') + 101);

        if (!$this->isResidential) {
            $propertyData['bedrooms'] = null;
            $propertyData['bathrooms'] = null;
            $propertyData['balconies'] = null;
            $propertyData['facing_direction'] = null;
            $propertyData['year_built'] = null;
        }

        $property = Property::create($propertyData);

        if ($this->thumbnail) {
            $property->addMedia($this->thumbnail->getRealPath())
                ->usingName(Str::slug($property->title) . '-thumbnail')
                ->toMediaCollection('thumbnail');
        }

        if (!empty($this->gallery_photos)) {
            foreach ($this->gallery_photos as $photo) {
                $property->addMedia($photo->getRealPath())->toMediaCollection('gallery');
            }
        }

        session()->flash('success', 'আপনার প্রোপার্টি সফলভাবে জমা হয়েছে এবং পর্যালোচনার অধীনে আছে।');
        $this->redirect(route('my-listings.index'));
    }

    /**
     * ভিউ ফাইল রেন্ডার করা।
     */
    public function render(): Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.property.create-form');
    }
}
