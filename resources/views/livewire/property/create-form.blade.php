<div>
    {{-- স্টেপ ইন্ডিকেটর / প্রোগ্রেস বার --}}
    <div class="progress mb-4" style="height: 20px;">
        <div class="progress-bar" role="progressbar" style="width: {{ ($currentStep - 1) * 33.33 }}%;" aria-valuenow="{{ ($currentStep - 1) * 33.33 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <form wire:submit.prevent="submitForm">

        {{-- ধাপ ১: মৌলিক তথ্য --}}
        @if ($currentStep == 1)
            <div class="step-wrapper">
                <h5 class="mb-3">মৌলিক তথ্য</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">প্রোপার্টির ধরন <span class="text-danger">*</span></label>
                        <select wire:model.live="property_type_id" class="form-select">
                            <option value="">নির্বাচন করুন</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('property_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    {{-- ... বাকি ফিল্ড ... --}}
                </div>
            </div>
        @endif

        {{-- ধাপ ২: বিস্তারিত বিবরণ --}}
        @if ($currentStep == 2)
            <div class="step-wrapper">
                <h5 class="mb-3">প্রোপার্টির বিবরণ</h5>
                <div class="row">
                    {{-- এই ফিল্ডটি সবার জন্য --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">আকার (স্কয়ার ফিট) <span class="text-danger">*</span></label>
                        <input type="number" wire:model="size_sqft" class="form-control">
                        @error('size_sqft') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- শুধুমাত্র আবাসিক প্রোপার্টির জন্য এই ফিল্ডগুলো দেখানো হবে --}}
                    @if ($isResidential)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">বেডরুম <span class="text-danger">*</span></label>
                            <input type="number" wire:model="bedrooms" class="form-control">
                            @error('bedrooms') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">বাথরুম <span class="text-danger">*</span></label>
                            <input type="number" wire:model="bathrooms" class="form-control">
                            @error('bathrooms') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">বারান্দা</label>
                            <input type="number" wire:model="balconies" class="form-control">
                            @error('balconies') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    {{-- ... বাকি ফিল্ড ... --}}
                </div>
            </div>
        @endif

        {{-- ধাপ ৩: মূল্য ও ঠিকানা --}}
        @if ($currentStep == 3)
            {{-- ... ধাপ ৩ এর ফিল্ডগুলো এখানে যোগ করুন ... --}}
        @endif

        {{-- ধাপ ৪: ছবি ও অন্যান্য --}}
        @if ($currentStep == 4)
            {{-- ... ধাপ ৪ এর ফিল্ডগুলো এখানে যোগ করুন ... --}}
        @endif

        {{-- নেভিগেশন বাটন --}}
        <div class="d-flex justify-content-between mt-4">
            @if ($currentStep > 1)
                <button type="button" class="btn btn-secondary" wire:click="previousStep">পূর্ববর্তী</button>
            @endif

            <div class="ms-auto">
                @if ($currentStep < 4)
                    <button type="button" class="btn btn-primary" wire:click="nextStep">পরবর্তী</button>
                @else
                    <button type="submit" class="btn btn-success">সাবমিট করুন</button>
                @endif
            </div>
        </div>

    </form>
</div>
