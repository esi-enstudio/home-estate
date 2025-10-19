<div>
    {{-- সফলভাবে সাবমিট হওয়ার পর বার্তা দেখানোর জন্য --}}
    @if (session()->has('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
        <div class="text-center">
            <a href="{{ route('home') }}" class="btn btn-primary">হোম পেজে ফিরে যান</a>
            <a href="{{ route('property.create') }}" class="btn btn-secondary">নতুন প্রোপার্টি যোগ করুন</a>
        </div>
    @else

        {{-- স্টেপ ইন্ডিকেটর / প্রোগ্রেস বার --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-1">
                <span class="fs-6 fw-semibold">ধাপ {{ $currentStep }} / 6</span>
                <span class="fs-6 fw-semibold">{{ round(($currentStep) * 16.666666666666) }}% সম্পন্ন</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($currentStep) * 16.666666666666 }}%;" aria-valuenow="{{ ($currentStep) * 16.666666666666 }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <option value="{{ $type->id }}">{{ $type->name_bn }}</option>
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

                        {{-- Dependent Dropdowns --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">বিভাগ <span class="text-danger">*</span></label>
                            <select wire:model.live="division_id" class="form-select @error('division_id') is-invalid @enderror">
                                <option value="">-- বিভাগ নির্বাচন করুন --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                @endforeach
                            </select>
                            @error('division_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">জেলা <span class="text-danger">*</span></label>
                            <div wire:loading wire:target="division_id" class="text-muted small">জেলা লোড হচ্ছে...</div>
                            <select wire:model.live="district_id" class="form-select @error('district_id') is-invalid @enderror" @if(count($districts) == 0) disabled @endif>
                                <option value="">-- জেলা নির্বাচন করুন --</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->bn_name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">উপজেলা / থানা <span class="text-danger">*</span></label>
                            <div wire:loading wire:target="district_id" class="text-muted small">উপজেলা লোড হচ্ছে...</div>
                            <select wire:model.live="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror" @if(count($upazilas) == 0) disabled @endif>
                                <option value="">-- উপজেলা নির্বাচন করুন --</option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}">{{ $upazila->bn_name }}</option>
                                @endforeach
                            </select>
                            @error('upazila_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ইউনিয়ন (ঐচ্ছিক)</label>
                            <div wire:loading wire:target="upazila_id" class="text-muted small">ইউনিয়ন লোড হচ্ছে...</div>
                            <select wire:model="union_id" class="form-select @error('union_id') is-invalid @enderror" @if(count($unions) == 0) disabled @endif>
                                <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}">{{ $union->bn_name }}</option>
                                @endforeach
                            </select>
                            @error('union_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- বাকি ঠিকানা ফিল্ডগুলো --}}
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
                        {{-- থাম্বনেইল আপলোড --}}
                        <div class="col-12 mb-4">
                            <label class="form-label">প্রধান ছবি (থাম্বনেইল) <span class="text-danger">*</span></label>
                            <input type="file" wire:model="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
                            <div wire:loading wire:target="thumbnail" class="text-success mt-1">ছবি আপলোড হচ্ছে...</div>
                            @error('thumbnail') <span class="text-danger small">{{ $message }}</span> @enderror

                            @if ($thumbnail && !$errors->has('thumbnail'))
                                <div class="mt-2">
                                    <p>প্রিভিউ:</p>
                                    <img src="{{ $thumbnail->temporaryUrl() }}" width="200" class="img-thumbnail">
                                </div>
                            @endif
                        </div>

                        {{-- গ্যালারি ছবি আপলোড --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">গ্যালারি ছবি (কমপক্ষে ২টি) <span class="text-danger">*</span></label>
                            <input type="file" wire:model="gallery_photos" class="form-control @error('gallery_photos.*') is-invalid @enderror" multiple>
                            <div wire:loading wire:target="gallery_photos" class="text-success mt-1">ছবিগুলো আপলোড হচ্ছে...</div>
                            @error('gallery_photos.*') <span class="text-danger small">{{ $message }}</span> @enderror
                            @error('gallery_photos') <span class="text-danger small">{{ $message }}</span> @enderror

                            @if ($gallery_photos)
                                <div class="mt-3">
                                    <h6>ছবি প্রিভিউ:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($gallery_photos as $photo)
                                            @if(!$errors->has('gallery_photos.*'))
                                                <img src="{{ $photo->temporaryUrl() }}" width="100" class="img-thumbnail">
                                            @endif
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

            {{-- =================================================================== --}}
            {{-- ধাপ ৫: গুগল ম্যাপস লিংক (Google Maps Link) --}}
            {{-- =================================================================== --}}
            @if ($currentStep == 5)
                <div class="step-wrapper animate__animated animate__fadeIn">
                    <h5 class="mb-4 border-bottom pb-2">ম্যাপস ও অক্ষাংশ, দ্রাঘিমাংশ</h5>
                    <div class="row">
                        {{-- গুগল ম্যাপস লিংক --}}
                        <div class="col-12 mb-3">
                            <label class="form-label">গুগল ম্যাপস লিংক</label>
                            <input type="url" wire:model="google_maps_location_link" class="form-control" placeholder="https://maps.app.goo.gl/rgN3GStCFhpgz1Xs8...">
                        </div>

                        {{-- গুগল ম্যাপস লিংক --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">অক্ষাংশ (Latitude)</label>
                            <input type="url" wire:model="latitude" class="form-control" placeholder="24.65498">
                        </div>

                        {{-- গুগল ম্যাপস লিংক --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">দ্রাঘিমাংশ (Longitude)</label>
                            <input type="url" wire:model="longitude" class="form-control" placeholder="90.987456">
                        </div>
                    </div>
                </div>
            @endif

            {{-- =================================================================== --}}
            {{-- ধাপ ৬: সচরাচর জিজ্ঞাসিত প্রশ্ন (FAQs) --}}
            {{-- =================================================================== --}}
            @if ($currentStep == 6)
                <div class="step-wrapper animate__animated animate__fadeIn">
                    <h5 class="mb-4 border-bottom pb-2">সচরাচর জিজ্ঞাসিত প্রশ্ন (FAQ)</h5>
                    <p class="text-muted">ভাড়াটিয়া বা ক্রেতাদের সাধারণ প্রশ্নগুলোর উত্তর এখানে যোগ করুন। যেমন: "গ্যাস সংযোগ আছে কি?", "গাড়ি পার্কিং এর ব্যবস্থা কি?"</p>

                    @foreach ($faqs as $index => $faq)
                        <div class="card mb-3" wire:key="faq-{{ $index }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="mb-3">
                                            <label class="form-label">প্রশ্ন {{ $index + 1 }}</label>
                                            <input type="text"
                                                   wire:model="faqs.{{ $index }}.question"
                                                   class="form-control @error('faqs.'.$index.'.question') is-invalid @enderror"
                                                   placeholder="প্রশ্নটি এখানে লিখুন">
                                            @error('faqs.'.$index.'.question') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">উত্তর {{ $index + 1 }}</label>
                                            <textarea wire:model="faqs.{{ $index }}.answer"
                                                      class="form-control @error('faqs.'.$index.'.answer') is-invalid @enderror"
                                                      rows="3"
                                                      placeholder="উত্তরটি এখানে লিখুন"></textarea>
                                            @error('faqs.'.$index.'.answer') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center">
                                        {{-- প্রথম আইটেমটি ছাড়া বাকিগুলো মুছে ফেলার অপশন থাকবে --}}
                                        @if ($index > 0)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    wire:click.prevent="removeFaq({{ $index }})"
                                                    title="মুছে ফেলুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button type="button" class="btn btn-primary mt-2" wire:click.prevent="addFaq">
                        <i class="fas fa-plus me-2"></i> নতুন প্রশ্ন যোগ করুন
                    </button>
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
                    @if ($currentStep < 6)
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
