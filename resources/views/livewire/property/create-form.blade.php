<div>
    {{-- সফলভাবে সাবমিট হওয়ার পর বার্তা দেখানোর জন্য --}}
    @if (session()->has('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
        <div class="text-center">
            <a href="{{ route('home') }}" class="btn btn-primary">হোম পেজে ফিরে যান</a>
            <a href="{{ route('properties.create') }}" class="btn btn-secondary">নতুন প্রোপার্টি যোগ করুন</a>
        </div>
    @else

        {{-- স্টেপ ইন্ডিকেটর / প্রোগ্রেস বার --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-1">
                <span class="fs-6 fw-semibold">ধাপ {{ $currentStep }} / 4</span>
                <span class="fs-6 fw-semibold">{{ ($currentStep) * 25 }}% সম্পন্ন</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($currentStep) * 25 }}%;" aria-valuenow="{{ ($currentStep) * 25 }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>


        <form wire:submit.prevent="submitForm">

            {{-- =================================================================== --}}
            {{-- ধাপ ১: মৌলিক তথ্য (Basic Information) --}}
            {{-- =================================================================== --}}
            @if ($currentStep == 1)
                <div class="step-wrapper animate__animated animate__fadeIn">
                    <h5 class="mb-4 border-bottom pb-2">মৌলিক তথ্য</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">প্রোপার্টির ধরন <span class="text-danger">*</span></label>
                            <select wire:model.live="property_type_id" class="form-select @error('property_type_id') is-invalid @enderror">
                                <option value="">-- ধরন নির্বাচন করুন --</option>
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_en }}</option>
                                @endforeach
                            </select>
                            @error('property_type_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">উদ্দেশ্য <span class="text-danger">*</span></label>
                            <select wire:model="purpose" class="form-select @error('purpose') is-invalid @enderror">
                                <option value="rent">ভাড়া</option>
                                <option value="sell">বিক্রয়</option>
                            </select>
                            @error('purpose') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" wire:model="title" class="form-control @error('title') is-invalid @enderror" placeholder="যেমন: বসুন্ধরায় ৩ বেডের সুন্দর অ্যাপার্টমেন্ট">
                            @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">বিস্তারিত বর্ণনা <span class="text-danger">*</span></label>
                            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="প্রোপার্টির সুযোগ-সুবিধা, পরিবেশ এবং অন্যান্য বিবরণ লিখুন..."></textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">কবে থেকে পাওয়া যাবে <span class="text-danger">*</span></label>
                            <input type="date" wire:model="available_from" class="form-control @error('available_from') is-invalid @enderror">
                            @error('available_from') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================================================== --}}
            {{-- ধাপ ২: বিস্তারিত বিবরণ (Property Specifications) --}}
            {{-- =================================================================== --}}
            @if ($currentStep == 2)
                <div class="step-wrapper animate__animated animate__fadeIn">
                    <h5 class="mb-4 border-bottom pb-2">প্রোপার্টির বিবরণ</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">আকার (স্কয়ার ফিট) <span class="text-danger">*</span></label>
                            <input type="number" wire:model="size_sqft" class="form-control @error('size_sqft') is-invalid @enderror" placeholder="e.g., 1200">
                            @error('size_sqft') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- শুধুমাত্র আবাসিক প্রোপার্টির জন্য --}}
                        @if ($isResidential)
                            <div class="col-md-4 mb-3">
                                <label class="form-label">বেডরুম সংখ্যা <span class="text-danger">*</span></label>
                                <input type="number" wire:model="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" placeholder="e.g., 3">
                                @error('bedrooms') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">বাথরুম সংখ্যা <span class="text-danger">*</span></label>
                                <input type="number" wire:model="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" placeholder="e.g., 2">
                                @error('bathrooms') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">বারান্দা সংখ্যা</label>
                                <input type="number" wire:model="balconies" class="form-control @error('balconies') is-invalid @enderror" placeholder="e.g., 2">
                                @error('balconies') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">দিক (Facing)</label>
                                <select wire:model="facing_direction" class="form-select">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="South">দক্ষিণ</option>
                                    <option value="North">উত্তর</option>
                                    <option value="East">পূর্ব</option>
                                    <option value="West">পশ্চিম</option>
                                    <option value="South-East">দক্ষিণ-পূর্ব</option>
                                    <option value="South-West">দক্ষিণ-পশ্চিম</option>
                                    <option value="North-East">উত্তর-পূর্ব</option>
                                    <option value="North-West">উত্তর-পশ্চিম</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">নির্মিত সাল</label>
                                <input type="number" wire:model="year_built" class="form-control" placeholder="e.g., 2020" max="{{ date('Y') }}">
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ফ্লোর লেভেল</label>
                            <input type="text" wire:model="floor_level" class="form-control" placeholder="যেমন: ৫ তলা (১২ তলার মধ্যে)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">মোট ফ্লোর সংখ্যা</label>
                            <input type="number" wire:model="total_floors" class="form-control" placeholder="e.g., 12">
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================================================== --}}
            {{-- ধাপ ৩: মূল্য ও ঠিকানা (Pricing & Location) --}}
            {{-- =================================================================== --}}
            @if ($currentStep == 3)
                <div class="step-wrapper animate__animated animate__fadeIn">
                    <h5 class="mb-4 border-bottom pb-2">মূল্য ও ঠিকানা</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                @if($purpose === 'rent') মাসিক ভাড়া @else বিক্রয় মূল্য @endif
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" wire:model="rent_price" class="form-control @error('rent_price') is-invalid @enderror" placeholder="টাকার পরিমাণ">
                            @error('rent_price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        @if($purpose === 'rent')
                            <div class="col-md-4 mb-3">
                                <label class="form-label">সার্ভিস চার্জ</label>
                                <input type="number" wire:model="service_charge" class="form-control" placeholder="e.g., 3000">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">সিকিউরিটি ডিপোজিট</label>
                                <input type="number" wire:model="security_deposit" class="form-control" placeholder="e.g., 50000">
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <label class="form-label">মূল্য আলোচনা সাপেক্ষে?</label>
                            <select wire:model="is_negotiable" class="form-select">
                                <option value="fixed">ফিক্সড</option>
                                <option value="negotiable">আলোচনা সাপেক্ষ</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <h6>ঠিকানা</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">এলাকা <span class="text-danger">*</span></label>
                            <input type="text" wire:model="address_area" class="form-control @error('address_area') is-invalid @enderror" placeholder="যেমন: বসুন্ধরা আবাসিক এলাকা, ধানমন্ডি">
                            @error('address_area') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">পোস্ট কোড</label>
                            <input type="text" wire:model="address_zipcode" class="form-control" placeholder="e.g., 1229">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">রাস্তার ঠিকানা <span class="text-danger">*</span></label>
                            <textarea wire:model="address_street" class="form-control @error('address_street') is-invalid @enderror" rows="3" placeholder="বাড়ি নং, রোড নং, ব্লক"></textarea>
                            @error('address_street') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================================================== --}}
            {{-- ধাপ ৪: ছবি ও অন্যান্য তথ্য (Media & Others) --}}
            {{-- =================================================================== --}}
            @if ($currentStep == 4)
                <div class="step-wrapper animate__animated animate__fadeIn">
                    <h5 class="mb-4 border-bottom pb-2">ছবি ও অন্যান্য তথ্য</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">প্রোপার্টির ছবি <span class="text-danger">*</span></label>
                            <input type="file" wire:model="photos" class="form-control" multiple>
                            <div wire:loading wire:target="photos" class="text-success mt-1">ছবি আপলোড হচ্ছে...</div>
                            @error('photos.*') <span class="text-danger small">{{ $message }}</span> @enderror

                            {{-- ছবি প্রিভিউ --}}
                            @if ($photos)
                                <div class="mt-3">
                                    <h6>ছবি প্রিভিউ:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($photos as $photo)
                                            <img src="{{ $photo->temporaryUrl() }}" width="100" class="img-thumbnail">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">ইউটিউব বা ভিডিও লিংক</label>
                            <input type="url" wire:model="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">বাড়ির নিয়মকানুন (House Rules)</label>
                            <textarea wire:model="house_rules" class="form-control" rows="4" placeholder="যেমন: ব্যাচেলরদের জন্য নিয়ম, পোষা প্রাণী রাখা যাবে কি না, ইত্যাদি।"></textarea>
                        </div>
                    </div>
                </div>
            @endif

            {{-- নেভিগেশন বাটন --}}
            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                <div>
                    @if ($currentStep > 1)
                        <button type="button" class="btn btn-secondary px-4" wire:click="previousStep" wire:loading.attr="disabled">
                            <i class="fas fa-arrow-left me-2"></i> পূর্ববর্তী
                        </button>
                    @endif
                </div>

                <div class="ms-auto">
                    @if ($currentStep < 4)
                        <button type="button" class="btn btn-primary px-4" wire:click="nextStep" wire:loading.attr="disabled">
                            পরবর্তী <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    @else
                        <button type="submit" class="btn btn-success px-5" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fas fa-check me-2"></i> সাবমিট করুন
                        </span>
                            <span wire:loading>
                            সাবমিট হচ্ছে...
                        </span>
                        </button>
                    @endif
                </div>
            </div>
        </form>
    @endif
</div>
