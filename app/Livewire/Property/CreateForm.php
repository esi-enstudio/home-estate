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

    public ?Property $property = null;
    public bool $isEditMode = false;

    // হেল্পার প্রোপার্টি
    public $propertyTypes;
    public bool $isResidential = false;

    public $existingThumbnailUrl;
    public $existingGalleryPhotos = [];

    public function mount(Property $property = null): void
    {
        $this->propertyTypes = PropertyType::orderBy('name_bn')->get();
        $this->divisions = Division::all();

        if ($property->exists) {
            $this->property = $property;
            $this->isEditMode = true;
            $this->fillFormWithPropertyData();
        } else {
            // Create মোডের জন্য ডিফল্ট মান
            $this->purpose = 'rent';
            $this->rent_type = 'month';
            $this->is_negotiable = 'fixed';
            $this->available_from = now()->format('Y-m-d');
            $this->faqs = [['question' => '', 'answer' => '']];
        }
    }

    protected function fillFormWithPropertyData(): void
    {
        // --- ধাপ ১: মৌলিক তথ্য ---
        $this->property_type_id = $this->property->property_type_id;
        $this->purpose = $this->property->purpose;
        $this->title = $this->property->title;
        $this->description = $this->property->description;
        // Carbon অবজেক্টকে HTML date input এর জন্য 'Y-m-d' ফরম্যাটে রূপান্তর করা
        $this->available_from = $this->property->available_from->format('Y-m-d');

        // --- ধাপ ২: বিস্তারিত বিবরণ ---
        $this->size_sqft = $this->property->size_sqft;
        $this->floor_level = $this->property->floor_level;
        $this->total_floors = $this->property->total_floors;
        // আবাসিক ফিল্ড
        $this->bedrooms = $this->property->bedrooms;
        $this->bathrooms = $this->property->bathrooms;
        $this->balconies = $this->property->balconies;
        $this->facing_direction = $this->property->facing_direction;
        $this->year_built = $this->property->year_built;

        // --- ধাপ ৩: মূল্য ও ঠিকানা ---
        $this->rent_price = $this->property->rent_price;
        $this->rent_type = $this->property->rent_type;
        $this->service_charge = $this->property->service_charge;
        $this->security_deposit = $this->property->security_deposit;
        $this->is_negotiable = $this->property->is_negotiable;

        // Dependent Dropdown এর জন্য লোকেশন ডেটা লোড এবং সেট করা
        $this->division_id = $this->property->division_id;
        if ($this->division_id) {
            $this->districts = District::where('division_id', $this->division_id)->get();
        }

        $this->district_id = $this->property->district_id;
        if ($this->district_id) {
            $this->upazilas = Upazila::where('district_id', $this->district_id)->get();
        }

        $this->upazila_id = $this->property->upazila_id;
        if ($this->upazila_id) {
            $this->unions = Union::where('upazila_id', $this->upazila_id)->get();
        }

        $this->union_id = $this->property->union_id;

        // বাকি ঠিকানা ফিল্ড
        $this->address_area = $this->property->address_area;
        $this->address_street = $this->property->address_street;
        $this->address_zipcode = $this->property->address_zipcode;

        // --- ধাপ ৪: ছবি ও অন্যান্য তথ্য ---
        // দ্রষ্টব্য: ফাইল ইনপুট ফিল্ড সরাসরি পপুলেট করা যায় না।
        // এর পরিবর্তে, আমরা বিদ্যমান ছবিগুলো প্রিভিউ হিসেবে দেখাব।
        // এর জন্য আপনাকে mount() মেথডে existing media লোড করতে হবে।

        $this->video_url = $this->property->video_url;
        $this->house_rules = $this->property->house_rules;

        // --- ধাপ ৫: ম্যাপ ---
        // $this->latitude = $this->property->latitude;
        // $this->longitude = $this->property->longitude;

        // --- ধাপ ৬: FAQ ---
        // যদি ডেটাবেজে FAQ null থাকে, তাহলে একটি খালি সারি দিয়ে শুরু হবে
        $this->faqs = $this->property->faqs ?? [['question' => '', 'answer' => '']];

        // --- সবশেষে, isResidential ফ্ল্যাগটি সেট করার জন্য এই মেথডটি কল করা হচ্ছে ---
        // এটি নিশ্চিত করবে যে এডিট ফর্মে আবাসিক/বাণিজ্যিক ফিল্ডগুলো সঠিকভাবে দেখাচ্ছে।
        $this->updatedPropertyTypeId($this->property_type_id);

        // --- বিদ্যমান ছবিগুলো লোড করা ---
        $this->existingThumbnailUrl = $this->property->getFirstMediaUrl('thumbnail', 'thumb');

        // গ্যালারির সব মিডিয়া অবজেক্ট আনা হচ্ছে
        $this->existingGalleryPhotos = $this->property->getMedia('gallery');
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
            // --- ছবির জন্য শর্তসাপেক্ষ ভ্যালিডেশন ---
            // তৈরি মোডে থাম্বনেইল বাধ্যতামূলক, এডিট মোডে নয়
            'thumbnail' => ($this->isEditMode ? 'nullable' : 'required') . '|image|max:2048',
            // তৈরি মোডে গ্যালারি ছবি বাধ্যতামূলক, এডিট মোডে নয়
            'gallery_photos' => ($this->isEditMode ? 'nullable' : 'required') . '|array|min:2|max:10',
            'gallery_photos.*' => 'image|max:2048',
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
    public function save(): void
    {
        // ১. ফর্মের ডেটা ভ্যালিডেট করা
        $validatedData = $this->validate();

        // ২. ছবি ছাড়া বাকি সব ভ্যালিডেটেড ডেটা সংগ্রহ করা
        $propertyData = collect($validatedData)->except(['thumbnail', 'gallery_photos'])->toArray();

        // ৩. শর্তসাপেক্ষ ফিল্ডগুলো সঠিকভাবে সেট করা (Create এবং Update উভয় ক্ষেত্রেই প্রযোজ্য)
        $propertyData['bedrooms'] = $this->isResidential ? $this->bedrooms : null;
        $propertyData['bathrooms'] = $this->isResidential ? $this->bathrooms : null;
        $propertyData['balconies'] = $this->isResidential ? $this->balconies : null;
        $propertyData['facing_direction'] = $this->isResidential ? $this->facing_direction : null;
        $propertyData['year_built'] = $this->isResidential ? $this->year_built : null;

        // FAQ ডেটা যদি খালি না থাকে, তাহলেই শুধু সেভ করা হবে
        // এটি নিশ্চিত করে যে যদি ইউজার সব FAQ মুছে ফেলে, তাহলে ডেটাবেজে null সেভ হবে
        $propertyData['faqs'] = !empty($this->faqs[0]['question']) ? $this->faqs : null;

        // ৪. এডিট মোড নাকি তৈরি মোড, তা নির্ধারণ করে কাজ করা
        if ($this->isEditMode) {
            // ----- আপডেট লজিক -----

            // a. মূল প্রোপার্টির তথ্য আপডেট করা
            $this->property->update($propertyData);

            // b. মিডিয়া (ছবি) আপডেট করা
            $this->handleMediaUpdate($this->property);

            session()->flash('success', 'আপনার প্রোপার্টি সফলভাবে আপডেট হয়েছে।');

        } else {
            // ----- তৈরি করার লজিক -----

            // a. নতুন প্রোপার্টির জন্য অতিরিক্ত ডেটা যোগ করা
            $propertyData['user_id'] = Auth::id();
            $propertyData['slug'] = Str::slug($this->title) . '-' . uniqid();
            $propertyData['property_code'] = 'BHA-' . (Property::max('id') + 101);

            // b. ডেটাবেজে নতুন প্রোপার্টি তৈরি করা
            $property = Property::create($propertyData);

            // c. নতুন প্রোপার্টির সাথে মিডিয়া যোগ করা
            $this->handleMediaUpdate($property);

            session()->flash('success', 'আপনার প্রোপার্টি সফলভাবে জমা হয়েছে এবং পর্যালোচনার অধীনে আছে।');
        }

        // ৫. সফলভাবে কাজ শেষ হওয়ার পর লিস্ট পেজে রিডাইরেক্ট করা
        $this->redirect(route('properties.my-list'));
    }

    /**
     * মিডিয়া ফাইল (থাম্বনেইল ও গ্যালারি) ম্যানেজ করার জন্য একটি হেল্পার মেথড।
     * এটি নতুন ছবি যোগ করে এবং পুরোনো ছবি মুছে ফেলে।
     */
    protected function handleMediaUpdate(Property $property): void
    {
        // ক. থাম্বনেইল আপডেট
        if ($this->thumbnail) {
            // ->toMediaCollection() মেথডটি পুরোনো ছবিটি স্বয়ংক্রিয়ভাবে মুছে ফেলে নতুনটি যোগ করে,
            // কারণ আমরা মডেলে এটিকে ->singleFile() হিসেবে ডিফাইন করেছি।
            $property->addMedia($this->thumbnail->getRealPath())
                ->usingName(Str::slug($property->title) . '-thumbnail')
                ->toMediaCollection('thumbnail');
        }

        // খ. গ্যালারি ছবি আপডেট
        if (!empty($this->gallery_photos)) {
            // প্রথমে পুরোনো সব গ্যালারি ছবি মুছে ফেলা (ঐচ্ছিক, কিন্তু সেরা অনুশীলন)
            // আপনি যদি পুরোনো ছবি রেখে নতুনগুলো যোগ করতে চান, তাহলে এই লাইনটি কমেন্ট করে দিন।
            $property->clearMediaCollection('gallery');

            // নতুন ছবিগুলো যোগ করা
            foreach ($this->gallery_photos as $photo) {
                $property->addMedia($photo->getRealPath())
                    ->toMediaCollection('gallery');
            }
        }
    }

    public function render()
    {
        return view('livewire.property.create-form');
    }
}
