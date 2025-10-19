<?php

namespace App\Livewire\Property;

use App\Models\District;
use App\Models\Division;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Union;
use App\Models\Upazila;
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
    public $video_url, $house_rules, $google_maps_location_link, $latitude, $longitude;
    public $available_from;
    public $thumbnail; // থাম্বনেইল ছবির জন্য
    public $gallery_photos = []; // গ্যালারি ছবির জন্য

    // Location IDs
    public $division_id;
    public $district_id;
    public $upazila_id;
    public $union_id;

    // Collections for dropdowns
    public $divisions = [];
    public $districts = [];
    public $upazilas = [];
    public $unions = [];

    public array $faqs = []; // FAQ রাখার জন্য

    // হেল্পার প্রোপার্টি
    public $propertyTypes;
    public bool $isResidential = false;

    public function mount(): void
    {
        // এখানে আপনার ডেটাবেজ থেকে ডেটা লোড করুন
        $this->propertyTypes = PropertyType::orderBy('name_en')->get();
        $this->available_from = now()->format('Y-m-d');

        // সব বিভাগ লোড করা হলো
        $this->divisions = Division::all();

        // ডিফল্টভাবে একটি খালি FAQ যোগ করা হলো
        $this->faqs = [['question' => '', 'answer' => '']];
    }

    /**
     * FAQ অ্যারেতে নতুন একটি খালি আইটেম যোগ করে।
     */
    public function addFaq(): void
    {
        $this->faqs[] = ['question' => '', 'answer' => ''];
    }

    /**
     * নির্দিষ্ট ইন্ডেক্স থেকে একটি FAQ আইটেম মুছে ফেলে।
     */
    public function removeFaq($index): void
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs); // অ্যারের ইন্ডেক্সগুলো পুনরায় সাজানো
    }

    /**
     * যখন Division পরিবর্তন হবে, তখন District গুলো লোড হবে।
     */
    public function updatedDivisionId($value): void
    {
        $this->districts = District::where('division_id', $value)->get();
        // পরবর্তী ড্রপডাউনগুলো রিসেট করা
        $this->reset(['district_id', 'upazila_id', 'union_id', 'upazilas', 'unions']);
    }

    /**
     * যখন District পরিবর্তন হবে, তখন Upazila গুলো লোড হবে।
     */
    public function updatedDistrictId($value): void
    {
        $this->upazilas = Upazila::where('district_id', $value)->get();
        // পরবর্তী ড্রপডাউনগুলো রিসেট করা
        $this->reset(['upazila_id', 'union_id', 'unions']);
    }

    /**
     * যখন Upazila পরিবর্তন হবে, তখন Union গুলো লোড হবে।
     */
    public function updatedUpazilaId($value): void
    {
        $this->unions = Union::where('upazila_id', $value)->get();
        $this->reset('union_id');
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
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',

            // ধাপ ৪
            'thumbnail' => 'required|image|max:2048', // থাম্বনেইল বাধ্যতামূলক
            'gallery_photos' => 'required|array|min:2|max:10', // গ্যালারিতে কমপক্ষে ২টি ছবি
            'gallery_photos.*' => 'image|max:2048', // প্রতিটি ছবির জন্য
            'video_url' => 'nullable|url',
            'house_rules' => 'nullable|string|max:2000',

            // ধাপ ৫
            'google_maps_location_link' => 'nullable|url',
            'latitude' => 'nullable',
            'longitude' => 'nullable',

            // ধাপ ৬
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs.*.answer|string|max:255',
            'faqs.*.answer' => 'required_with:faqs.*.question|string|max:1000',
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
            'division_id.required' => 'বিভাগ নির্বাচন করুন।',
            'district_id.required' => 'জেলা নির্বাচন করুন।',
            'upazila_id.required' => 'উপজেলা নির্বাচন করুন।',
            'photos.required' => 'কমপক্ষে একটি ছবি আপলোড করুন।',
            'thumbnail.required' => 'একটি প্রধান ছবি (থাম্বনেইল) দেওয়া আবশ্যক।',
            'gallery_photos.required' => 'কমপক্ষে দুইটি গ্যালারি ছবি আপলোড করুন।',
            'gallery_photos.min' => 'গ্যালারির জন্য কমপক্ষে দুইটি ছবি দিন।',
            'video_url.url' => 'সঠিক ভিডিও ইউআরএল দিন।',
            'google_maps_location_link.url' => 'গুগল ম্যাপ এর ইউআরএল দিন।',
            'faqs.*.question.required_with' => 'প্রশ্নটি পূরণ করুন অথবা এই সারিটি মুছে ফেলুন।',
            'faqs.*.answer.required_with' => 'উত্তরটি পূরণ করুন অথবা এই সারিটি মুছে ফেলুন।',
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
        } elseif ($step == 5) {
            $fieldsToValidate = ['google_maps_location_link', 'latitude', 'latitude'];
        } elseif ($step == 6) {
            $fieldsToValidate = ['faqs', 'faqs.*.question', 'faqs.*.answer'];
        }

        $this->validate(collect($this->rules())->only($fieldsToValidate)->toArray());
    }

    public function nextStep(): void
    {
        $this->validateStep($this->currentStep);
        if ($this->currentStep < 6) {
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
        // সাবমিটের আগে সম্পূর্ণ ফর্ম ভ্যালিডেট করা
        $validatedData = $this->validate();
        $user = Auth::user();

        // Property টেবিলে সেভ করার জন্য একটি ডেটা অ্যারে তৈরি করা হচ্ছে
        // এখানে ছবির অ্যারে বাদ দিয়ে बाकी সব ভ্যালিডেটেড ডেটা নেওয়া হলো
        $propertyData = collect($validatedData)->except(['thumbnail', 'gallery_photos'])->toArray();

        // ম্যানুয়ালি বাকি ডেটাগুলো যোগ করা হচ্ছে
        $propertyData['user_id'] = $user->id;
        $propertyData['slug'] = Str::slug($this->title) . '-' . uniqid(); // ইউনিক স্লাগ তৈরি
        $propertyData['property_code'] = 'BHA-' . (Property::max('id') + 101); // ইউনিক কোড তৈরি

        // $validatedData থেকে এই ফিল্ডগুলো স্বয়ংক্রিয়ভাবে $propertyData তে চলে এসেছে,
        // কারণ এগুলো rules() মেথডে ডিফাইন করা ছিল।
        // স্বচ্ছতার জন্য নিচে আবার দেখানো হলো:
        $propertyData['division_id'] = $this->division_id;
        $propertyData['district_id'] = $this->district_id;
        $propertyData['upazila_id'] = $this->upazila_id;
        $propertyData['union_id'] = $this->union_id;
        // ----------------------------------------------------------------

        // শুধুমাত্র আবাসিক প্রোপার্টির জন্য প্রযোজ্য ফিল্ডগুলো সেট করা
        $propertyData['bedrooms'] = $this->isResidential ? $this->bedrooms : null;
        $propertyData['bathrooms'] = $this->isResidential ? $this->bathrooms : null;
        $propertyData['balconies'] = $this->isResidential ? $this->balconies : null;
        $propertyData['facing_direction'] = $this->isResidential ? $this->facing_direction : null;
        $propertyData['year_built'] = $this->isResidential ? $this->year_built : null;

        $propertyData['faqs'] = $this->faqs;

        // ১. প্রথমে ছবি ছাড়া প্রোপার্টি তৈরি করুন
        $property = Property::create($propertyData);

        // ২. এখন প্রোপার্টির সাথে মিডিয়া ফাইলগুলো যুক্ত করুন
        if ($this->thumbnail) {
            $property->addMedia($this->thumbnail->getRealPath())
                ->usingName(Str::slug($property->title) . '-thumbnail') // SEO-friendly নাম
                ->toMediaCollection('thumbnail');
        }

        if (!empty($this->gallery_photos)) {
            foreach ($this->gallery_photos as $photo) {
                $property->addMedia($photo->getRealPath())
                    ->toMediaCollection('gallery');
            }
        }

        session()->flash('success', 'আপনার প্রোপার্টি সফলভাবে জমা হয়েছে এবং পর্যালোচনার অধীনে আছে।');

        $this->reset(); // ফর্ম রিসেট করা
    }

    public function render()
    {
        return view('livewire.property.create-form');
    }
}
