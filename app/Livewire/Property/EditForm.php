<?php

namespace App\Livewire\Property;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EditForm extends Component
{
    use WithFileUploads;

    public Property $property;

    // স্টেপ ম্যানেজমেন্ট
    public int $currentStep = 1;

    // --- ফর্মের সব ফিল্ড ---
    public $property_type_id, $purpose, $title, $description, $available_from;
    public $size_sqft, $bedrooms, $bathrooms, $balconies, $facing_direction, $year_built, $floor_level, $total_floors;
    public $rent_price, $service_charge, $security_deposit, $is_negotiable;
    public $division_id, $district_id, $upazila_id, $union_id, $address_area, $address_zipcode, $address_street;
    public $thumbnail, $gallery_photos = [], $video_url, $house_rules;

    // --- হেল্পার প্রোপার্টি ---
    public bool $isResidential = false;
    public $propertyTypes = [], $divisions = [], $districts = [], $upazilas = [], $unions = [];
    public $existingThumbnailUrl;
    public $existingGallery = [];

    /**
     * কম্পোনেন্ট মাউন্ট হওয়ার সময় বিদ্যমান ডেটা লোড করা।
     * @throws AuthorizationException
     */
    public function mount(Property $property): void
    {
        // পলিসি দিয়ে অথোরাইজেশন চেক করা
        $this->authorize('update', $property);

        $this->property = $property;

        // ডেটা দিয়ে ফর্মের প্রোপার্টিগুলো পূর্ণ করা
        $this->fill($property->toArray());

        // লোকেশন আইডি এবং ড্রপডাউন ডেটা লোড করা
        $this->divisions = Division::orderBy('name')->get();
        $this->districts = District::where('division_id', $this->division_id)->get();
        $this->upazilas = Upazila::where('district_id', $this->district_id)->get();
        $this->unions = Union::where('upazila_id', $this->upazila_id)->get();

        // isResidential ফ্ল্যাগ ঠিকভাবে সেট করা
        $this->updatedPropertyTypeId($this->property_type_id);

        // বিদ্যমান মিডিয়া লোড করা
        $this->existingThumbnailUrl = $property->getFirstMediaUrl('thumbnail', 'thumb');
        $this->existingGallery = $property->getMedia('gallery')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl('preview')
            ];
        })->toArray();
    }

    /**
     * ভ্যালিডেশন রুলস (এডিট ফর্মের জন্য)।
     */
    protected function rules(): array
    {
        // CreateForm থেকে প্রায় সব রুল একই, শুধু ছবিগুলো `nullable` হবে
        return [
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
            'thumbnail' => 'nullable|image|max:2048', // <-- পরিবর্তন
            'gallery_photos' => 'nullable|array|max:10', // <-- পরিবর্তন
            'gallery_photos.*' => 'image|max:2048',
            'video_url' => 'nullable|url',
            'house_rules' => 'nullable|string|max:2000',
        ];
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

    // --- ডাইনামিক ফিল্ডের জন্য Updated Hooks ---
    // CreateForm থেকে updatedPropertyTypeId, updatedDivisionId, updatedDistrictId, updatedUpazilaId মেথডগুলো হুবহু কপি করে আনুন
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
    // CreateForm থেকে nextStep এবং previousStep মেথডগুলো হুবহু কপি করে আনুন
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
     * বিদ্যমান গ্যালারি ছবি মুছে ফেলার জন্য।
     */
    public function removeExistingGalleryImage($mediaId): void
    {
        $mediaItem = Media::find($mediaId);
        // নিরাপত্তা: নিশ্চিত করুন যে মিডিয়া আইটেমটি এই প্রোপার্টিরই অংশ
        if ($mediaItem && $mediaItem->model_id === $this->property->id) {
            $mediaItem->delete();
            // ভিউ রিফ্রেশ করার জন্য বিদ্যমান গ্যালারি অ্যারে আপডেট করা
            $this->existingGallery = array_filter($this->existingGallery, fn($image) => $image['id'] !== $mediaId);
            session()->flash('success', 'ছবি সফলভাবে মুছে ফেলা হয়েছে।');
        }
    }

    /**
     * ফাইনাল আপডেট।
     */
    public function submitForm(): void
    {
        $validatedData = $this->validate();

        $propertyData = collect($validatedData)->except(['thumbnail', 'gallery_photos'])->toArray();

        $this->property->update($propertyData);

        // নতুন থাম্বনেইল আপলোড করা হলে পুরোনোটি সরিয়ে নতুনটি যোগ করা
        if ($this->thumbnail) {
            $this->property->clearMediaCollection('thumbnail');
            $this->property->addMedia($this->thumbnail->getRealPath())
                ->usingName(Str::slug($this->property->title) . '-thumbnail')
                ->toMediaCollection('thumbnail');
        }

        // নতুন গ্যালারি ছবি যোগ করা
        if (!empty($this->gallery_photos)) {
            foreach ($this->gallery_photos as $photo) {
                $this->property->addMedia($photo->getRealPath())->toMediaCollection('gallery');
            }
        }

        session()->flash('success', 'প্রোপার্টি সফলভাবে আপডেট করা হয়েছে।');
        $this->redirect(route('my-listings.index'));
    }

    public function render(): Factory|View
    {
        return view('livewire.property.edit-form');
    }
}
