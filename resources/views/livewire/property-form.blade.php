<div>
    {{-- Improved Stepper Progress Bar --}}
    <div class="stepper-wrapper">
        @php
            $steps = [
                1 => 'মৌলিক তথ্য',
                2 => 'বিস্তারিত',
                3 => 'অবস্থান',
                4 => 'মূল্য ও প্রাপ্যতা',
                5 => 'ছবি ও অন্যান্য',
            ];
        @endphp

        @foreach($steps as $step => $title)
            <div class="stepper-item {{ $currentStep == $step ? 'active' : '' }} {{ $currentStep > $step ? 'completed' : '' }}" wire:key="step-{{ $step }}">
                <div class="stepper-icon" @if($currentStep > $step) wire:click="goToStep({{ $step }})" @endif>
                    @if($currentStep > $step)
                        <i class="fas fa-check"></i> {{-- Font Awesome check icon --}}
                    @else
                        {{ $step }}
                    @endif
                </div>
                <div class="stepper-label">{{ $title }}</div>
            </div>
        @endforeach
    </div>

    {{-- Form Content --}}
    <form wire:submit.prevent="save" class="mt-5 pt-3">
        <div class="card">
            <div class="card-body">

                {{-- Step 1: Core Information --}}
                <div class="{{ $currentStep == 1 ? 'd-block' : 'd-none' }}">
                    <h5 class="card-title mb-4">মৌলিক তথ্য (ধাপ ১/{{ $totalSteps }})</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">প্রোপার্টির ধরন <span class="text-danger">*</span></label>
                            <select wire:model.live="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror">
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_bn }}</option>
                                @endforeach
                            </select>
                            @error('property_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if(in_array('purpose', $visibleFields))
                            <div class="col-md-6">
                                <label class="form-label">উদ্দেশ্য <span class="text-danger">*</span></label>
                                <select wire:model="purpose" class="form-select @error('purpose') is-invalid @enderror">
                                    <option value="rent">ভাড়া</option>
                                    <option value="sell">বিক্রয়</option>
                                </select>
                                @error('purpose') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        @if(in_array('title', $visibleFields))
                            <div class="col-12">
                                <label class="form-label">শিরোনাম <span class="text-danger">*</span></label>
                                <input type="text" wire:model="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: ধানমন্ডিতে ৩ বেডের সুন্দর ফ্ল্যাট">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        @if(in_array('description', $visibleFields))
                            <div class="col-12">
                                <label class="form-label">বিস্তারিত বর্ণনা <span class="text-danger">*</span></label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="আপনার প্রোপার্টির বিস্তারিত তথ্য এখানে লিখুন..."></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Step 2: Property Specifications --}}
                <div class="{{ $currentStep == 2 ? 'd-block' : 'd-none' }}">
                    <h5 class="card-title mb-4">প্রোপার্টির বিস্তারিত (ধাপ ২/{{ $totalSteps }})</h5>

                    @if(!empty($visibleFields))
                        <div class="row g-3">
                            {{-- Size --}}
                            @if(in_array('size_sqft', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">আকার (স্কয়ার ফিট) <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="size_sqft" class="form-control @error('size_sqft') is-invalid @enderror" placeholder="যেমন: ১২০০">
                                    @error('size_sqft') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Bedrooms --}}
                            @if(in_array('bedrooms', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">বেডরুম <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" placeholder="যেমন: ৩">
                                    @error('bedrooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Bathrooms --}}
                            @if(in_array('bathrooms', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">বাথরুম <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" placeholder="যেমন: ২">
                                    @error('bathrooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Balconies --}}
                            @if(in_array('balconies', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">বারান্দা</label>
                                    <input type="number" wire:model="balconies" class="form-control @error('balconies') is-invalid @enderror" placeholder="যেমন: ১">
                                    @error('balconies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Floor Level --}}
                            @if(in_array('floor_level', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">ফ্লোর লেভেল</label>
                                    <input type="text" wire:model="floor_level" class="form-control @error('floor_level') is-invalid @enderror" placeholder="যেমন: ৫ তলা">
                                    @error('floor_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Total Floors --}}
                            @if(in_array('total_floors', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">বিল্ডিং-এর মোট ফ্লোর</label>
                                    <input type="number" wire:model="total_floors" class="form-control @error('total_floors') is-invalid @enderror" placeholder="যেমন: ১০">
                                    @error('total_floors') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Facing Direction --}}
                            @if(in_array('facing_direction', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">কোন দিকে মুখ করা</label>
                                    <input type="text" wire:model="facing_direction" class="form-control @error('facing_direction') is-invalid @enderror" placeholder="যেমন: দক্ষিণ">
                                    @error('facing_direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Year Built --}}
                            @if(in_array('year_built', $visibleFields))
                                <div class="col-md-4">
                                    <label class="form-label">নির্মাণের বছর</label>
                                    <input type="number" wire:model="year_built" class="form-control @error('year_built') is-invalid @enderror" placeholder="YYYY" min="1900" max="{{ date('Y') }}">
                                    @error('year_built') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            {{-- Amenities --}}
                            @if(in_array('amenities', $visibleFields))
                                <hr class="my-3 col-12">
                                <div class="col-12">
                                    <h6 class="mb-3">সুবিধা সমূহ (Amenities)</h6>
                                    <div class="row">
                                        @if(isset($amenities) && $amenities->count() > 0)
                                            @foreach($amenities as $type => $group)
                                                <div class="col-12 mt-2">
                                                    <strong class="text-capitalize d-block border-bottom pb-1 mb-2">{{ $type }}</strong>
                                                </div>
                                                @foreach($group as $amenity)
                                                    <div class="col-lg-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            {{-- Amenity Checkbox --}}
                                                            <div class="form-check flex-grow-1">
                                                                <input
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="{{ $amenity->id }}"
                                                                    id="amenity-{{ $amenity->id }}"
                                                                    wire:model.live="selectedAmenities"
                                                                >
                                                                <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                                                    @if($amenity->icon_class)
                                                                        <i class="{{ $amenity->icon_class }} me-1"></i>
                                                                    @endif
                                                                    {{ $amenity->name }}
                                                                </label>
                                                            </div>

                                                            {{-- Details Input Field (Conditional) --}}
                                                            @if(in_array($amenity->id, $selectedAmenities))
                                                                <div class="ms-2" style="width: 50%;">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-sm"
                                                                        wire:model="amenityDetails.{{ $amenity->id }}"
                                                                        placeholder="বিস্তারিত (যেমন: ১টি গাড়ি)"
                                                                    >
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @else
                                            <p class="text-muted">কোনো সুবিধা যোগ করা হয়নি।</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">এই ধরনের প্রোপার্টির জন্য কোনো বিস্তারিত তথ্য প্রয়োজন নেই।</p>
                            <p>অনুগ্রহ করে "পরবর্তী ধাপে যান" বাটনে ক্লিক করুন।</p>
                        </div>
                    @endif
                </div>

                {{-- Step 3: Location --}}
                <div class="{{ $currentStep == 3 ? 'd-block' : 'd-none' }}">
                    <h5 class="card-title mb-4">অবস্থানের তথ্য (ধাপ ৩/{{ $totalSteps }})</h5>

                    @php
                        // লোকেশন সংক্রান্ত ফিল্ডগুলোর একটি তালিকা
                        $locationFields = ['division_id', 'district_id', 'upazila_id', 'union_id', 'address_area', 'address_street', 'address_zipcode', 'google_maps_location_link'];
                        // visibleFields এর সাথে উপরের তালিকার কোনো মিল আছে কি না তা চেক করা
                        $hasLocationFields = !empty(array_intersect($locationFields, $visibleFields));
                    @endphp

                    {{-- এখন এই নতুন ভ্যারিয়েবলটি দিয়ে কন্ডিশন চেক করা হচ্ছে --}}
                    @if($hasLocationFields)
                        <div class="row g-3">
                            <!-- Division Dropdown -->
                            @if(in_array('division_id', $visibleFields))
                                <div class="col-md-6 col-lg-3">
                                    <label for="division_id" class="form-label">বিভাগ <span class="text-danger">*</span></label>
                                    <select id="division_id" wire:model.live="division_id" class="form-select @error('division_id') is-invalid @enderror">
                                        <option value="">-- বিভাগ নির্বাচন করুন --</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('division_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- District Dropdown -->
                            @if(in_array('district_id', $visibleFields))
                                <div class="col-md-6 col-lg-3">
                                    <label for="district_id" class="form-label">জেলা <span class="text-danger">*</span></label>
                                    <select id="district_id" wire:model.live="district_id" class="form-select @error('district_id') is-invalid @enderror" @if(count($districts) == 0) disabled @endif>
                                        <option value="">-- জেলা নির্বাচন করুন --</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->bn_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Upazila Dropdown -->
                            @if(in_array('upazila_id', $visibleFields))
                                <div class="col-md-6 col-lg-3">
                                    <label for="upazila_id" class="form-label">উপজেলা <span class="text-danger">*</span></label>
                                    <select id="upazila_id" wire:model.live="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror" @if(count($upazilas) == 0) disabled @endif>
                                        <option value="">-- উপজেলা নির্বাচন করুন --</option>
                                        @foreach($upazilas as $upazila)
                                            <option value="{{ $upazila->id }}">{{ $upazila->bn_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('upazila_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Union Dropdown -->
                            @if(in_array('union_id', $visibleFields))
                                <div class="col-md-6 col-lg-3">
                                    <label for="union_id" class="form-label">ইউনিয়ন (ঐচ্ছিক)</label>
                                    <select id="union_id" wire:model="union_id" class="form-select @error('union_id') is-invalid @enderror" @if(count($unions) == 0) disabled @endif>
                                        <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                        @foreach($unions as $union)
                                            <option value="{{ $union->id }}">{{ $union->bn_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('union_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <hr class="my-3 col-12">

                            <!-- Address Area -->
                            @if(in_array('address_area', $visibleFields))
                                <div class="col-md-6">
                                    <label for="address_area" class="form-label">এলাকা <span class="text-danger">*</span></label>
                                    <input type="text" id="address_area" wire:model="address_area" class="form-control @error('address_area') is-invalid @enderror" placeholder="যেমন: ধানমন্ডি, গুলশান ১">
                                    @error('address_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Address Zipcode -->
                            @if(in_array('address_zipcode', $visibleFields))
                                <div class="col-md-6">
                                    <label for="address_zipcode" class="form-label">পোস্ট কোড (ঐচ্ছিক)</label>
                                    <input type="text" id="address_zipcode" wire:model="address_zipcode" class="form-control @error('address_zipcode') is-invalid @enderror">
                                    @error('address_zipcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Address Street -->
                            @if(in_array('address_street', $visibleFields))
                                <div class="col-12">
                                    <label for="address_street" class="form-label">রাস্তা/বাড়ির ঠিকানা <span class="text-danger">*</span></label>
                                    <textarea id="address_street" wire:model="address_street" class="form-control @error('address_street') is-invalid @enderror" rows="3" placeholder="বাড়ি নং, রাস্তা নং, ব্লক/সেক্টর ইত্যাদি"></textarea>
                                    @error('address_street') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <hr class="my-3 col-12">

                            <!-- Google Maps Link -->
                            @if(in_array('google_maps_location_link', $visibleFields))
                                <div class="col-12">
                                    <label for="google_maps_location_link" class="form-label">গুগল ম্যাপ লিংক</label>
                                    <input type="url" id="google_maps_location_link" wire:model="google_maps_location_link" class="form-control @error('google_maps_location_link') is-invalid @enderror" placeholder="https://maps.app.goo.gl/...">
                                    @error('google_maps_location_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Latitude -->
                            @if(in_array('latitude', $visibleFields))
                                <div class="col-md-6">
                                    <label for="latitude" class="form-label">অক্ষাংশ (Latitude)</label>
                                    <input type="text" id="latitude" wire:model="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="24.654987">
                                    @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Longitude -->
                            @if(in_array('longitude', $visibleFields))
                                <div class="col-md-6">
                                    <label for="longitude" class="form-label">দ্রাঘিমাংশ (Longitude)</label>
                                    <input type="text" id="longitude" wire:model="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="90.654987">
                                    @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- যদি কোনো কারণে লোকেশন ফিল্ড কনফিগারেশনে না থাকে --}}
                        <p class="text-center text-muted">এই ধরনের প্রোপার্টির জন্য কোনো অবস্থানের তথ্য প্রয়োজন নেই। অনুগ্রহ করে পরবর্তী ধাপে যান।</p>
                    @endif
                </div>

                {{-- Step 4: Pricing & Availability --}}
                <div class="{{ $currentStep == 4 ? 'd-block' : 'd-none' }}">
                    <h5 class="card-title mb-4">মূল্য ও প্রাপ্যতা (ধাপ ৪/{{ $totalSteps }})</h5>
                    <div class="row g-3">
                        @if(in_array('rent_price', $visibleFields))
                            <div class="col-md-6"><label class="form-label">ভাড়া/মূল্য <span class="text-danger">*</span></label><input type="number" wire:model="rent_price" class="form-control"></div>
                        @endif
                        @if(in_array('rent_type', $visibleFields))
                            <div class="col-md-6"><label class="form-label">ভাড়ার ধরন</label><select wire:model="rent_type" class="form-select">
                                    <option value="day">দৈনিক</option>
                                    <option value="week">সাপ্তাহিক</option>
                                    <option value="month">মাসিক</option>
                                    <option value="year">বাৎসরিক</option>
                                </select></div>
                        @endif
                        @if(in_array('service_charge', $visibleFields))
                            <div class="col-md-6"><label class="form-label">সার্ভিস চার্জ (মাসিক)</label><input type="number" wire:model="service_charge" class="form-control"></div>
                        @endif
                        @if(in_array('security_deposit', $visibleFields))
                            <div class="col-md-6"><label class="form-label">সিকিউরিটি ডিপোজিট</label><input type="number" wire:model="security_deposit" class="form-control"></div>
                        @endif
                        @if(in_array('is_negotiable', $visibleFields))
                            <div class="col-md-6"><label class="form-label">আলোচনা সাপেক্ষ</label><select wire:model="is_negotiable" class="form-select">
                                    <option value="fixed">নির্দিষ্ট</option>
                                    <option value="negotiable">আলোচনা সাপেক্ষ</option>
                                </select></div>
                        @endif
                        @if(in_array('available_from', $visibleFields))
                            <div class="col-md-6"><label class="form-label">কবে থেকে পাওয়া যাবে <span class="text-danger">*</span></label><input type="date" wire:model="available_from" class="form-control"></div>
                        @endif
                    </div>
                </div>

                {{-- Step 5: Media & Others --}}
                <div class="{{ $currentStep == 5 ? 'd-block' : 'd-none' }}">
                    <h5 class="card-title mb-4">ছবি ও অন্যান্য তথ্য (ধাপ ৫/{{ $totalSteps }})</h5>
                    <div class="row g-4">
                        <!-- Thumbnail Upload -->
                        <div class="col-12">
                            <label for="thumbnail" class="form-label fw-bold">থাম্বনেইল ছবি (প্রচ্ছদ)</label>
                            <p class="text-muted small">এটি আপনার লিস্টিংয়ের প্রধান ছবি হিসেবে দেখানো হবে।</p>

                            <input type="file" wire:model="thumbnail" id="thumbnail" class="form-control">
                            <div wire:loading wire:target="thumbnail" class="text-primary mt-1">আপলোড হচ্ছে...</div>
                            @error('thumbnail') <div class="text-danger mt-1">{{ $message }}</div> @enderror

                            <!-- Preview -->
                            <div class="mt-3">
                                @if ($thumbnail)
                                    <p>নতুন প্রিভিউ:</p>
                                    <img src="{{ $thumbnail->temporaryUrl() }}" class="img-thumbnail" width="200">
                                @elseif($existingThumbnailUrl)
                                    <p>বর্তমান ছবি:</p>
                                    <img src="{{ $existingThumbnailUrl }}" class="img-thumbnail" width="200">
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Gallery Upload -->
                        <div class="col-12">
                            <label for="gallery" class="form-label fw-bold">গ্যালারির ছবি</label>
                            <p class="text-muted small">একাধিক ছবি সিলেক্ট করতে পারেন।</p>

                            <input type="file" wire:model="gallery" id="gallery" class="form-control" multiple>
                            <div wire:loading wire:target="gallery" class="text-primary mt-1">আপলোড হচ্ছে...</div>
                            @error('gallery.*') <div class="text-danger mt-1">{{ $message }}</div> @enderror

                            <!-- Previews -->
                            <div class="mt-3">
                                <!-- Existing Gallery Images -->
                                @if(!empty($existingGallery))
                                    <p>বিদ্যমান ছবি:</p>
                                    <div class="row g-2">
                                        @foreach($existingGallery as $image)
                                            <div class="col-3 position-relative">
                                                <img src="{{ $image['url'] }}" class="img-thumbnail w-100">
                                                <button type="button" wire:click="removeImage({{ $image['id'] }})" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" title="মুছে ফেলুন">
                                                    &times;
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- New Gallery Previews -->
                                @if (!empty($gallery))
                                    <p class="mt-3">নতুন প্রিভিউ:</p>
                                    <div class="row g-2">
                                        @foreach ($gallery as $image)
                                            <div class="col-3">
                                                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail w-100">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(in_array('video_url', $visibleFields))
                            <div class="col-12"><label class="form-label">ভিডিও লিংক (ইউটিউব)</label><input type="url" wire:model="video_url" class="form-control"></div>
                        @endif
                        @if(in_array('house_rules', $visibleFields))
                            <div class="col-12"><label class="form-label">বাড়ির নিয়মাবলী</label><textarea wire:model="house_rules" class="form-control"></textarea></div>
                        @endif

                        {{-- Additional Features Repeater --}}
                        @if(in_array('additional_features', $visibleFields))
                            <hr class="my-3">
                            <div class="col-12">
                                <h5 class="mb-3">অন্যান্য সুবিধা (Additional Features)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%;">ফিচারের নাম</th>
                                            <th style="width: 50%;">বিবরণ (ঐচ্ছিক)</th>
                                            <th style="width: 10%;" class="text-center">মুছুন</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($additional_features) > 0)
                                            @foreach($additional_features as $index => $feature)
                                                <tr wire:key="feature-{{ $index }}">
                                                    <td>
                                                        <input type="text"
                                                               wire:model="additional_features.{{ $index }}.name"
                                                               class="form-control"
                                                               placeholder="যেমন: জেনারেটর">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               wire:model="additional_features.{{ $index }}.description"
                                                               class="form-control"
                                                               placeholder="যেমন: ২৪ ঘন্টা ব্যাকআপ">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                wire:click="removeFeature({{ $index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    কোনো অতিরিক্ত সুবিধা যোগ করা হয়নি।
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-outline-primary w-100" wire:click="addFeature">
                                                    <i class="fas fa-plus me-2"></i> নতুন সুবিধা যোগ করুন
                                                </button>
                                            </td>
                                        </tr>
                                        <tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- FAQ Repeater --}}
                        @if(in_array('faqs', $visibleFields))
                            <hr class="my-4">
                            <div class="col-12">
                                <h5 class="mb-3">সচরাচর জিজ্ঞাসিত প্রশ্ন (FAQ)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%;">প্রশ্ন</th>
                                            <th style="width: 50%;">উত্তর</th>
                                            <th style="width: 10%;" class="text-center">মুছুন</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($faqs) > 0)
                                            @foreach($faqs as $index => $faq)
                                                <tr wire:key="faq-{{ $index }}">
                                                    <td>
                                                        <input type="text"
                                                               wire:model="faqs.{{ $index }}.question"
                                                               class="form-control"
                                                               placeholder="প্রশ্ন এখানে লিখুন...">
                                                        @error('faqs.'.$index.'.question') <small class="text-danger">{{ $message }}</small> @enderror
                                                    </td>
                                                    <td>
                                                        <textarea
                                                            wire:model="faqs.{{ $index }}.answer"
                                                            class="form-control"
                                                            rows="2"
                                                            placeholder="উত্তর এখানে লিখুন..."></textarea>
                                                        @error('faqs.'.$index.'.answer') <small class="text-danger">{{ $message }}</small> @enderror
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                wire:click="removeFaq({{ $index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    কোনো প্রশ্ন ও উত্তর যোগ করা হয়নি।
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-outline-primary w-100" wire:click="addFaq">
                                                    <i class="fas fa-plus me-2"></i> নতুন প্রশ্ন যোগ করুন
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="card-footer bg-light d-flex justify-content-between">
                @if ($currentStep > 1)
                    <button type="button" class="btn btn-secondary" wire:click="previousStep">আগের ধাপে যান</button>
                @else
                    <div></div>
                @endif

                @if ($currentStep < $totalSteps)
                    <button type="button" class="btn btn-primary" wire:click="nextStep">পরবর্তী ধাপে যান</button>
                @else
                    <button type="submit" class="btn btn-success">
                    <span wire:loading.remove>
                        {{ $isEditing ? 'লিস্টিং আপডেট করুন' : 'লিস্টিং সাবমিট করুন' }}
                    </span>
                        <span wire:loading>প্রসেস হচ্ছে...</span>
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
