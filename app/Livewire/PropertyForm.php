<?php

namespace App\Livewire;

use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Division;
use App\Models\District;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads; // ছবি আপলোডের জন্য

class PropertyForm extends Component
{
    use WithFileUploads;

    // ফর্ম ফিল্ডের জন্য পাবলিক প্রোপার্টি (স্কিমা অনুযায়ী)
    // --- Model & State ---
    public ?Property $listing = null;
    public bool $isEditing = false;
    public int $currentStep = 1;
    public int $totalSteps = 5;

    // --- Step 1: Core Information ---
    public $property_type_id = '';
    public string $title = '';
    public string $description = '';
    public string $purpose = 'rent';

    // --- Step 2: Property Specifications ---
    public ?int $bedrooms = null;
    public ?int $bathrooms = null;
    public int $balconies = 0;
    public ?int $size_sqft = null;
    public ?string $floor_level = null;
    public ?int $total_floors = null;
    public ?string $facing_direction = null;
    public ?int $year_built = null;
    public array $additional_features = [];
    public array $selectedAmenities = [];
    public array $amenityDetails = [];

    // --- Step 3: Location ---
    public $division_id = '';
    public $district_id = '';
    public $upazila_id = '';
    public $union_id = '';
    public string $address_street = '';
    public string $address_area = '';
    public ?string $address_zipcode = null;
    public ?string $google_maps_location_link = null;
    public ?float $latitude = null;
    public ?float $longitude = null;

    // --- Step 4: Pricing & Availability ---
    public int $rent_price = 0;
    public string $rent_type = 'month';
    public int $service_charge = 0;
    public int $security_deposit = 0;
    public string $is_negotiable = 'fixed';
    public bool $is_available = true;
    public ?string $available_from = null;

    // --- Step 5: Media & Rules ---
    public $thumbnail; // নতুন থাম্বনেইল আপলোডের জন্য
    public $gallery = []; // নতুন গ্যালারি ছবি আপলোডের জন্য

    public $existingThumbnailUrl = null; // এডিট মোডে বিদ্যমান থাম্বনেইল দেখানোর জন্য
    public $existingGallery = []; // এডিট মোডে বিদ্যমান গ্যালারি দেখানোর জন্য
    public ?string $video_url = null;
    public ?string $house_rules = null;
    public array $faqs = [];


    // --- SEO (সাধারণত অ্যাডমিন প্যানেল থেকে ম্যানেজ করা হয়, তবে এখানেও রাখা যেতে পারে) ---
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;

    // Helper properties (এগুলো ডেটাবেজে সেভ হবে না)
    public array $visibleFields = [];
    public $divisions = [], $districts = [], $upazilas = [], $unions = [];

    public function mount(Property $listing = null): void
    {
        // প্রাথমিক ভাবে সব ডিভিশন লোড করা
        $this->divisions = Division::all();

        if ($listing && $listing->exists) {
            // --- এডিট মোড ---
            $this->listing = $listing;
            $this->isEditing = true;

            // মডেল থেকে ডেটা দিয়ে ফর্ম ফিল করা (ম্যানুয়াল পদ্ধতিতে)
            $this->hydrateFormFromModel();

            // বিদ্যমান প্রোপার্টির সাথে যুক্ত থাকা amenity-গুলোর ID লোড করা হচ্ছে
            $this->selectedAmenities = $this->listing->amenities()->pluck('amenities.id')->toArray();

            // amenityDetails অ্যারেটি পূরণ করা হচ্ছে
            foreach ($this->listing->amenities as $amenity) {
                $this->amenityDetails[$amenity->id] = $amenity->pivot->details;
            }

            // এডিট মোডের জন্য নির্ভরশীল লোকেশন ড্রপডাউনগুলো লোড করা
            if ($this->division_id) {
                $this->districts = District::where('division_id', $this->division_id)->get();
            }
            if ($this->district_id) {
                $this->upazilas = Upazila::where('district_id', $this->district_id)->get();
            }
            if ($this->upazila_id) {
                // ইউনিয়ন লোড করার কোড এখানে যোগ করা হয়েছে
                $this->unions = Union::where('upazila_id', $this->upazila_id)->get();
            }

        } else {
            // --- নতুন এন্ট্রি মোড ---
            $this->listing = new Property();
            $this->available_from = now()->format('Y-m-d');
        }

        // শুরুতে বা এডিট মোডে Property Type অনুযায়ী ফিল্ডগুলো দেখানো
        if ($this->property_type_id) {
            $this->updateVisibleFields($this->property_type_id);
        }

        if ($this->isEditing) {
            // বিদ্যমান থাম্বনেইলের URL লোড করা
            $this->existingThumbnailUrl = $this->listing->getFirstMediaUrl('thumbnail', 'preview');

            // বিদ্যমান গ্যালারির ছবিগুলো লোড করা
            $this->existingGallery = $this->listing->getMedia('gallery')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl('thumbnail')
                ];
            })->toArray();

            $this->listing = $listing;
            $this->isEditing = true;

            // --- additional_features ডেটা রূপান্তরের লজিক ---
            $featuresData = $listing->additional_features ?? [];
            $formattedFeatures = [];

            // চেক করা হচ্ছে ডেটা KeyValue ফরম্যাটে আছে নাকি Repeater ফরম্যাটে
            if (!empty($featuresData) && array_is_list($featuresData)) {
                // Repeater format: [{"name": "...", "description": "..."}]
                // এটি ইতিমধ্যেই সঠিক ফরম্যাটে আছে, তাই সরাসরি ব্যবহার করা হবে
                $formattedFeatures = $featuresData;
            } else {
                // KeyValue format: {"key": "value"}
                // এটিকে Repeater ফরম্যাটে রূপান্তর করা হচ্ছে
                foreach ($featuresData as $key => $value) {
                    $formattedFeatures[] = ['name' => $key, 'description' => $value];
                }
            }
            $this->additional_features = $formattedFeatures;
            // --- রূপান্তর শেষ ---

            // fill() মেথডটি রূপান্তরের *পরে* কল করা ভালো, যাতে এটি অন্য ডেটা ওভাররাইট না করে
            $this->fill($listing->except('additional_features'));
        }
    }

    /**
     * Helper method to manually fill form properties from the model.
     * This provides better control, especially for cast attributes like JSON fields.
     */
    private function hydrateFormFromModel(): void
    {
        // Core Information
        $this->property_type_id = $this->listing->property_type_id;
        $this->title = $this->listing->title;
        $this->description = $this->listing->description;
        $this->purpose = $this->listing->purpose;

        // Property Specifications
        $this->bedrooms = $this->listing->bedrooms;
        $this->bathrooms = $this->listing->bathrooms;
        $this->balconies = $this->listing->balconies;
        $this->size_sqft = $this->listing->size_sqft;
        $this->floor_level = $this->listing->floor_level;
        $this->total_floors = $this->listing->total_floors;
        $this->facing_direction = $this->listing->facing_direction;
        $this->year_built = $this->listing->year_built;
        $this->additional_features = $this->listing->additional_features ?? []; // JSON field

        // Location
        $this->division_id = $this->listing->division_id;
        $this->district_id = $this->listing->district_id;
        $this->upazila_id = $this->listing->upazila_id;
        $this->union_id = $this->listing->union_id;
        $this->address_street = $this->listing->address_street;
        $this->address_area = $this->listing->address_area;
        $this->address_zipcode = $this->listing->address_zipcode;
        $this->google_maps_location_link = $this->listing->google_maps_location_link;
        $this->latitude = $this->listing->latitude;
        $this->longitude = $this->listing->longitude;

        // Pricing & Availability
        $this->rent_price = $this->listing->rent_price;
        $this->rent_type = $this->listing->rent_type;
        $this->service_charge = $this->listing->service_charge;
        $this->security_deposit = $this->listing->security_deposit;
        $this->is_negotiable = $this->listing->is_negotiable;
        $this->is_available = $this->listing->is_available;
        $this->available_from = optional($this->listing->available_from)->format('Y-m-d');

        // Media & Rules
        $this->video_url = $this->listing->video_url;
        $this->house_rules = $this->listing->house_rules;
        $this->faqs = $this->listing->faqs ?? []; // JSON field

        // SEO
        $this->meta_title = $this->listing->meta_title;
        $this->meta_description = $this->listing->meta_description;
        $this->meta_keywords = $this->listing->meta_keywords;
    }

    /**
     * Property Type অনুযায়ী কোন কোন ফিল্ড কোন ধাপে দেখাবে তার কনফিগারেশন।
     * এটি ফর্মের মূল চালিকাশক্তি।
     */
    private function getFieldConfig(?int $propertyTypeId): array
    {
        if (!$propertyTypeId) {
            return []; // যদি কোনো টাইপ সিলেক্ট না থাকে, তবে কিছুই দেখাবে না
        }

        // সব ধরনের প্রোপার্টির জন্য কমন ফিল্ডগুলো সংজ্ঞায়িত করা
        $baseConfig = [
            1 => ['property_type_id', 'title', 'description', 'purpose'],
            2 => ['size_sqft'],
            3 => ['division_id', 'district_id', 'upazila_id', 'union_id', 'address_area', 'address_street', 'address_zipcode', 'google_maps_location_link', 'latitude', 'longitude'],
            4 => ['rent_price', 'rent_type', 'service_charge', 'security_deposit', 'is_negotiable', 'available_from', 'is_available'],
            5 => ['video_url', 'house_rules', 'faqs', 'additional_features', 'meta_fields'],
        ];

        // নির্দিষ্ট Property Type অনুযায়ী ফিল্ড যোগ বা বিয়োগ করা
        $propertyTypeName = strtolower(PropertyType::find($propertyTypeId)->name ?? '');

        switch ($propertyTypeName) {
            case 'apartment':
            case 'flat':
                // Step 2-তে অ্যাপার্টমেন্ট-specific ফিল্ড যোগ করা
                $baseConfig[2] = array_merge($baseConfig[2], [
                    'bedrooms', 'bathrooms', 'balconies', 'floor_level', 'total_floors', 'facing_direction', 'year_built'
                ]);
                break;

            case 'commercial space':
            case 'office':
                // Step 2-তে কমার্শিয়াল-specific ফিল্ড যোগ করা
                $baseConfig[2] = array_merge($baseConfig[2], [
                    'floor_level', 'total_floors', 'year_built'
                ]);
                // অপ্রয়োজনীয় ফিল্ড বাদ দেওয়া (যদি থাকে)
                // উদাহরণস্বরূপ, কমার্শিয়াল স্পেসের জন্য 'bedrooms' দরকার নেই
                break;

            case 'land':
                // জমির জন্য অনেক ফিল্ড অপ্রয়োজনীয়, তাই সেগুলো বাদ দেওয়া হচ্ছে
                $baseConfig[2] = ['size_sqft']; // শুধু আকার থাকবে
                $baseConfig[4] = ['rent_price', 'is_negotiable', 'available_from']; // মূল্য সংক্রান্ত ফিল্ড কমানো হলো
                $baseConfig[5] = ['video_url', 'meta_fields']; // অন্যান্য ফিল্ড কমানো হলো
                break;

            // আপনি এখানে আরও Property Type-এর জন্য case যোগ করতে পারেন
            // case 'shop': ...

            default:
                // ডিফল্ট ক্ষেত্রে কোনো পরিবর্তন নেই
                break;
        }

        return $baseConfig;
    }

    // --- স্মার্ট ফিল্ড ম্যানেজমেন্ট শুরু ---
    public function updatedPropertyTypeId($value): void
    {
        $this->updateVisibleFields($value);
    }

    private function updateVisibleFields($typeId): void
    {
        if(!$typeId) {
            $this->visibleFields = [];
            return;
        }

        // ডাটাবেজ থেকে প্রোপার্টির ধরন খুঁজে বের করা
        $propertyType = PropertyType::find($typeId);
        if (!$propertyType) {
            $this->visibleFields = [];
            return;
        }

        // config/forms.php থেকে ফিল্ড কনফিগারেশন লোড করা
        $config = config('forms.property_fields');

        // *** মূল পরিবর্তন এখানে: $propertyType->slug ব্যবহার করা হচ্ছে ***
        $typeGroups = $config['by_type'][$propertyType->slug] ?? $config['common'];

        $fields = [];
        foreach ($typeGroups as $groupName) {
            $fields = array_merge($fields, $config['field_groups'][$groupName] ?? []);
        }

        $this->visibleFields = array_unique($fields);

        // ডিবাগিং এর জন্য (প্রয়োজনে আনকমেন্ট করুন)
        // dump($this->visibleFields);
    }
    // --- স্মার্ট ফিল্ড ম্যানেজমেন্ট শেষ ---

    // --- লোকেশন ম্যানেজমেন্ট শুরু ---
    public function updatedDivisionId($value): void
    {
        $this->districts = District::where('division_id', $value)->get();
        // বিভাগ পরিবর্তন হলে জেলা, উপজেলা এবং ইউনিয়ন রিসেট করা
        $this->reset(['district_id', 'upazila_id', 'union_id']);
        $this->upazilas = [];
        $this->unions = [];
    }

    public function updatedDistrictId($value): void
    {
        $this->upazilas = Upazila::where('district_id', $value)->get();
        // জেলা পরিবর্তন হলে উপজেলা এবং ইউনিয়ন রিসেট করা
        $this->reset(['upazila_id', 'union_id']);
        $this->unions = [];
    }

    public function updatedUpazilaId($value): void
    {
        // স্কিমা অনুযায়ী ইউনিয়ন nullable, তাই এটি ঐচ্ছিক হতে পারে
        // Union মডেল এবং টেবিল আপনার প্রোজেক্টে থাকতে হবে
        $this->unions = Union::where('upazila_id', $value)->get();

        $this->reset('union_id');
    }
    // --- লোকেশন ম্যানেজমেন্ট শেষ ---

    protected function rules(): array
    {
        // --- শর্তসাপেক্ষ ভ্যালিডেশনের জন্য প্রোপার্টির ধরন জানা ---
        $propertyTypeName = '';
        if ($this->property_type_id) {
            $propertyType = PropertyType::find($this->property_type_id);
            if ($propertyType) {
                $propertyTypeName = strtolower($propertyType->name_bn); // যেমন: 'অ্যাপার্টমেন্ট'
            }
        }

        $rules = [
            // --- ধাপ ১: মৌলিক তথ্য ---
            'property_type_id'      => ['required', 'integer', 'exists:property_types,id'],
            'title'                 => ['required', 'string', 'min:10', 'max:255'],
            'description'           => ['required', 'string', 'min:50'],
            'purpose'               => ['required', Rule::in(['rent', 'sell'])],

            // --- ধাপ ২: প্রোপার্টির বিস্তারিত ---
            'bedrooms'              => [
                // শুধুমাত্র 'অ্যাপার্টমেন্ট' বা এই জাতীয় ধরনের জন্য bedrooms বাধ্যতামূলক
                Rule::requiredIf(fn() => in_array('bedrooms', $this->visibleFields)),
                'nullable', 'integer', 'min:1'
            ],
            'bathrooms'             => [
                Rule::requiredIf(fn() => in_array('bathrooms', $this->visibleFields)),
                'nullable', 'integer', 'min:1'
            ],
            'balconies'             => ['nullable', 'integer', 'min:0'],
            'size_sqft'             => ['required', 'integer', 'min:1'],
            'floor_level'           => ['nullable', 'string', 'max:100'],
            'total_floors'          => ['nullable', 'integer', 'min:1'],
            'facing_direction'      => ['nullable', 'string', 'max:100'],
            'year_built'            => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'additional_features'   => ['nullable', 'array'],

            // --- ধাপ ৩: অবস্থান ---
            'division_id'           => ['required', 'integer', 'exists:divisions,id'],
            'district_id'           => ['required', 'integer', 'exists:districts,id'],
            'upazila_id'            => ['required', 'integer', 'exists:upazilas,id'],
            'union_id'              => ['nullable', 'integer', 'exists:unions,id'],
            'address_street'        => ['required', 'string', 'max:255'],
            'address_area'          => ['required', 'string', 'max:255'],
            'address_zipcode'       => ['nullable', 'string', 'max:20'],
            'google_maps_location_link' => ['nullable', 'url'],
            'latitude'              => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'             => ['nullable', 'numeric', 'between:-180,180'],

            // --- ধাপ ৪: মূল্য ও প্রাপ্যতা ---
            'rent_price'            => [
                // যদি purpose 'rent' হয়, তবেই এটি বাধ্যতামূলক
                Rule::requiredIf($this->purpose === 'rent'),
                'nullable', 'integer', 'min:0'
            ],
            'rent_type'             => [
                Rule::requiredIf($this->purpose === 'rent'),
                'nullable', Rule::in(['day', 'week', 'month', 'year'])
            ],
            'service_charge'        => ['nullable', 'integer', 'min:0'],
            'security_deposit'      => ['nullable', 'integer', 'min:0'],
            'is_negotiable'         => ['required', Rule::in(['negotiable', 'fixed'])],
            'is_available'          => ['boolean'],
            'available_from'        => ['required', 'date', 'after_or_equal:today'],

            // --- ধাপ ৫: মিডিয়া ও নিয়মকানুন ---
            'video_url'             => ['nullable', 'url'],
            'house_rules'           => ['nullable', 'string'],
            'faqs'                  => ['nullable', 'array'],
        ];

        // ছবি আপলোডের জন্য ভ্যালিডেশন
        // এডিট মোডে ছবি বাধ্যতামূলক নয়, কিন্তু create মোডে থাম্বনেইল বাধ্যতামূলক করা যেতে পারে
        $rules['thumbnail'] = [
            $this->isEditing ? 'nullable' : 'required',
            'image',
            'max:2048' // 2MB
        ];

        $rules['gallery.*'] = ['image', 'max:2048']; // প্রতিটি গ্যালারি ছবির জন্য

        return $rules;
    }

    // --- স্টেপ ম্যানেজমেন্ট ---
    public function goToStep($step): void
    {
        // শুধুমাত্র আগের স্টেপগুলোতেই ক্লিক করে যাওয়া যাবে
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function nextStep(): void
    {
        // পরবর্তী স্টেপে যাওয়ার আগে বর্তমান স্টেপের ডেটা ভ্যালিডেট করা
        $this->validateStep($this->currentStep);

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // --- Additional Features Management ---
    public function addFeature(): void
    {
        // এখন আমরা একটি নাম এবং বিবরণ সহ একটি নতুন অ্যারে যোগ করব
        $this->additional_features[] = ['name' => '', 'description' => ''];
    }

    public function removeFeature($index): void
    {
        unset($this->additional_features[$index]);
        $this->additional_features = array_values($this->additional_features);
    }

    // --- স্টেপ অনুযায়ী ভ্যালিডেশন (সম্পূর্ণ আপডেট করা) ---
    private function validateStep(int $step): void
    {
        // প্রতিটি স্টেপের জন্য ভ্যালিডেশন রুলস
        $rules = match ($step) {
            // ধাপ ১: মৌলিক তথ্য
            1 => [
                'property_type_id' => ['required', 'integer'],
                'title' => ['required', 'string', 'min:10', 'max:255'],
                'description' => ['required', 'string', 'min:50'],
                'purpose' => ['required', Rule::in(['rent', 'sell'])],
            ],

            // ধাপ ২: প্রোপার্টির বিস্তারিত
            2 => [
                'size_sqft' => ['required', 'integer', 'min:1'],
                // required_if এর পরিবর্তে visibleFields ব্যবহার করা হয়েছে, যা ডায়নামিক
                'bedrooms' => [Rule::requiredIf(in_array('bedrooms', $this->visibleFields)), 'nullable', 'integer', 'min:1'],
                'bathrooms' => [Rule::requiredIf(in_array('bathrooms', $this->visibleFields)), 'nullable', 'integer', 'min:1'],
                'balconies' => ['required', 'integer', 'min:0'],
                'floor_level' => ['nullable', 'string', 'max:100'],
                'total_floors' => ['nullable', 'integer', 'min:1'],
                'facing_direction' => ['nullable', 'string'],
                'year_built' => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            ],

            // ধাপ ৩: অবস্থান
            3 => [
                'division_id' => ['required', 'integer'],
                'district_id' => ['required', 'integer'],
                'upazila_id' => ['required', 'integer'],
                'union_id' => ['nullable', 'integer'], // স্কিমা অনুযায়ী ইউনিয়ন nullable
                'address_area' => ['required', 'string', 'max:255'],
                'address_street' => ['required', 'string'],
                'address_zipcode' => ['nullable', 'string', 'max:20'],
                'google_maps_location_link' => ['nullable', 'url'],
            ],

            // ধাপ ৪: মূল্য ও প্রাপ্যতা
            4 => [
                'rent_price' => ['required', 'integer', 'min:0'],
                'rent_type' => ['required', Rule::in(['day', 'week', 'month', 'year'])],
                'service_charge' => ['required', 'integer', 'min:0'],
                'security_deposit' => ['required', 'integer', 'min:0'],
                'is_negotiable' => ['required', Rule::in(['negotiable', 'fixed'])],
                'available_from' => ['required', 'date'],
            ],

            // ধাপ ৫: ছবি ও অন্যান্য (এখানে ছবি আপলোডের ভ্যালিডেশন যোগ করতে হবে)
            5 => [
                'video_url' => ['nullable', 'url'],
                'house_rules' => ['nullable', 'string'],
                // এখানে আপনি ছবি আপলোডের জন্য ভ্যালিডেশন যোগ করবেন
                // 'photos.*' => ['required', 'image', 'max:2048'],
            ],

            default => [],
        };

        $this->validate($rules);
    }

    /**
     * faqs অ্যারেতে একটি নতুন খালি প্রশ্ন-উত্তর আইটেম যোগ করে।
     */
    public function addFaq(): void
    {
        $this->faqs[] = ['question' => '', 'answer' => ''];
    }

    /**
     * faqs অ্যারে থেকে নির্দিষ্ট ইনডেক্সের আইটেমটি মুছে ফেলে।
     *
     * @param int $index
     */
    public function removeFaq(int $index): void
    {
        if (isset($this->faqs[$index])) {
            unset($this->faqs[$index]);
            // অ্যারের ইনডেক্সগুলো ঠিক করার জন্য (যেমন: 0, 1, 2...)
            $this->faqs = array_values($this->faqs);
        }
    }

    /**
     * এডিট মোডে থাকা অবস্থায় বিদ্যমান গ্যালারি ছবি মুছে ফেলার জন্য এই মেথডটি ব্যবহার করা হয়।
     *
     * @param int $mediaId
     * @return void
     */
    public function removeImage(int $mediaId): void
    {
        // শুধুমাত্র এডিট মোডেই এই কাজটি করা সম্ভব
        if (!$this->isEditing) {
            return;
        }

        // পলিসি দিয়ে অথোরাইজেশন (ঐচ্ছিক কিন্তু প্রস্তাবিত)
        $this->authorize('update', $this->listing);

        // নির্দিষ্ট ID দিয়ে মিডিয়া ফাইলটি খুঁজে বের করা
        $media = $this->listing->getMedia('gallery')->find($mediaId);

        // যদি মিডিয়া ফাইল পাওয়া যায়, তবেই সেটি মুছে ফেলা হবে
        if ($media) {
            $media->delete();
        }

        // রিয়েল-টাইমে প্রিভিউ থেকে ছবিটি সরিয়ে ফেলার জন্য existingGallery অ্যারেটি রিফ্রেশ করা
        $this->existingGallery = array_filter($this->existingGallery, fn($image) => $image['id'] != $mediaId);

        // একটি ফিডব্যাক মেসেজ দেখানো (ঐচ্ছিক)
        $this->dispatch('image-removed', 'ছবি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    public function save()
    {
        // সর্বশেষ সাবমিটের আগে সম্পূর্ণ ফর্ম ভ্যালিডেট করা
        $validatedData = $this->validate($this->rules());

        // পলিসি দিয়ে অথোরাইজেশন
        if ($this->isEditing) {
            $this->authorize('update', $this->listing);
        } else {
            $this->authorize('create', Property::class);
        }

        // --- additional_features ডেটা প্রস্তুত করা (KeyValue ফরম্যাটে রূপান্তর) ---
        $featuresToSave = [];
        foreach ($this->additional_features as $feature) {
            if (!empty($feature['name'])) { // খালি নাম সেভ করা হবে না
                $featuresToSave[$feature['name']] = $feature['description'] ?? '';
            }
        }

        // --- সম্পূর্ণ স্কিমা অনুযায়ী ডেটা প্রস্তুত করা ---
        $data = [
            // Core Information
            'property_type_id'      => $this->property_type_id,
            'title'                 => $this->title,
            'description'           => $this->description,
            'purpose'               => $this->purpose,

            // Pricing Details
            'rent_price'            => $this->rent_price,
            'rent_type'             => $this->rent_type,
            'service_charge'        => $this->service_charge,
            'security_deposit'      => $this->security_deposit,
            'is_negotiable'         => $this->is_negotiable,

            // Property Specifications (শর্তসাপেক্ষে যোগ করা)
            'bedrooms'              => in_array('bedrooms', $this->visibleFields) ? $this->bedrooms : null,
            'bathrooms'             => in_array('bathrooms', $this->visibleFields) ? $this->bathrooms : null,
            'balconies'             => in_array('balconies', $this->visibleFields) ? $this->balconies : 0,
            'size_sqft'             => $this->size_sqft,
            'floor_level'           => in_array('floor_level', $this->visibleFields) ? $this->floor_level : null,
            'total_floors'          => in_array('total_floors', $this->visibleFields) ? $this->total_floors : null,
            'facing_direction'      => in_array('facing_direction', $this->visibleFields) ? $this->facing_direction : null,
            'year_built'            => in_array('year_built', $this->visibleFields) ? $this->year_built : null,

            // Location
            'division_id'           => $this->division_id,
            'district_id'           => $this->district_id,
            'upazila_id'            => $this->upazila_id,
            'union_id'              => $this->union_id,
            'address_street'        => $this->address_street,
            'address_area'          => $this->address_area,
            'address_zipcode'       => $this->address_zipcode,
            'google_maps_location_link' => $this->google_maps_location_link,
            'latitude'              => $this->latitude,
            'longitude'             => $this->longitude,

            // Rules, Features & Media
            'house_rules'           => $this->house_rules,
            'faqs'                  => $this->faqs,
            'additional_features'   => $featuresToSave,
            'video_url'             => $this->video_url,

            // Status & Visibility
            'available_from'        => $this->available_from,
            'is_available'          => $this->is_available,

            // SEO
            'meta_title'            => $this->meta_title,
            'meta_description'      => $this->meta_description,
            'meta_keywords'         => $this->meta_keywords,
        ];

        if ($this->isEditing) {
            // শুধুমাত্র শিরোনাম পরিবর্তন হলেই স্লাগ আপডেট করা হবে
            if ($this->listing->title !== $this->title) {
                $data['slug'] = Str::slug($this->title) . '-' . $this->listing->id;
            }

            $this->listing->update($data);
            $property = $this->listing->fresh(); // ডেটাবেজ থেকে সর্বশেষ ডেটা রিফ্রেশ করে নেওয়া
            session()->flash('success', 'লিস্টিং সফলভাবে আপডেট হয়েছে।');
        } else {
            // নতুন লিস্টিং এর জন্য slug এবং property_code যোগ করা
            $data['slug'] = Str::slug($this->title) . '-' . uniqid();
            $data['property_code'] = 'BHARA-' . (Property::max('id') + 101);
            $data['status'] = 'pending'; // নতুন লিস্টিং এর ডিফল্ট স্ট্যাটাস

            $property = Auth::user()->properties()->create($data);
            session()->flash('success', 'নতুন লিস্টিং সফলভাবে তৈরি হয়েছে।');
        }

        // ******************************************************
        // --- মিডিয়া সেভ করার নতুন এবং আপডেট করা লজিক ---
        // ******************************************************

        // ১. নতুন থাম্বনেইল আপলোড করা হলে
        if ($this->thumbnail) {
            // যদি আগে কোনো থাম্বনেইল থাকে, সেটি মুছে ফেলা হবে (singleFile collection)
            $property->addMedia($this->thumbnail->getRealPath())
                ->toMediaCollection('thumbnail');
        }

        // ২. নতুন গ্যালারি ছবি আপলোড করা হলে
        if (!empty($this->gallery)) {
            foreach ($this->gallery as $image) {
                $property->addMedia($image->getRealPath())
                    ->toMediaCollection('gallery');
            }
        }
        // --- মিডিয়া সেভ করার লজিক শেষ ---

        // amenity_property পিভট টেবিলে ডেটা সিঙ্ক করা হচ্ছে
        if (in_array('amenities', $this->visibleFields)) {
            $amenitiesToSync = [];
            foreach ($this->selectedAmenities as $amenityId) {
                $amenitiesToSync[$amenityId] = [
                    'details' => $this->amenityDetails[$amenityId] ?? null
                ];
            }

            // পিভট টেবিলে অতিরিক্ত ডেটা সহ সিঙ্ক করা হচ্ছে
            $property->amenities()->sync($amenitiesToSync);
        }

        return redirect()->route('listings.index');
    }

    public function render()
    {
        $propertyTypes = PropertyType::all();

        // সব amenity লোড করে তাদের 'type' অনুযায়ী গ্রুপ করা হচ্ছে
        $amenities = Amenity::all()->groupBy('type');

        return view('livewire.property-form', compact('propertyTypes', 'amenities'));
    }
}
