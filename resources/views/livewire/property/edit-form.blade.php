<div>
    <h4 class="mb-4">"{{ $property->title }}" - এডিট করুন</h4>

    {{-- স্টেপ ইন্ডিকেটর / প্রোগ্রেস বার (CreateForm থেকে কপি করুন) --}}
    {{-- ... --}}

    <form wire:submit.prevent="submitForm">

        {{-- =================================================================== --}}
        {{-- ধাপ ১, ২, এবং ৩ --}}
        {{-- =================================================================== --}}
        {{-- এই ধাপগুলোর HTML কোড আপনার create-form.blade.php থেকে হুবহু কপি করে আনুন। --}}
        {{-- Livewire স্বয়ংক্রিয়ভাবে ইনপুট ফিল্ডগুলোতে বিদ্যমান ডেটা বসিয়ে দেবে। --}}

        @if ($currentStep == 1)
            {{-- create-form.blade.php থেকে ধাপ ১ এর কোড এখানে পেস্ট করুন --}}
        @endif
        @if ($currentStep == 2)
            {{-- create-form.blade.php থেকে ধাপ ২ এর কোড এখানে পেস্ট করুন --}}
        @endif
        @if ($currentStep == 3)
            {{-- create-form.blade.php থেকে ধাপ ৩ এর কোড এখানে পেস্ট করুন --}}
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
                        <label class="form-label">প্রধান ছবি (থাম্বনেইল) <small>(নতুন ছবি আপলোড করলে পুরোনোটি প্রতিস্থাপিত হবে)</small></label>
                        <div class="mb-2">
                            <p>বর্তমান ছবি:</p>
                            <img src="{{ $existingThumbnailUrl }}" width="200" class="img-thumbnail">
                        </div>
                        <input type="file" wire:model="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
                        @if ($thumbnail && !$errors->has('thumbnail'))
                            <div class="mt-2"><p>নতুন ছবির প্রিভিউ:</p><img src="{{ $thumbnail->temporaryUrl() }}" width="200" class="img-thumbnail"></div>
                        @endif
                        @error('thumbnail') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- গ্যালারি ছবি --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">গ্যালারি ছবি <small>(নতুন ছবি যোগ করুন)</small></label>
                        {{-- বিদ্যমান গ্যালারি ছবি দেখানো এবং ডিলিট করার অপশন --}}
                        @if (!empty($existingGallery))
                            <p>বিদ্যমান ছবি:</p>
                            <div class="d-flex flex-wrap gap-2 border p-2 rounded mb-3">
                                @foreach ($existingGallery as $image)
                                    <div class="position-relative">
                                        <img src="{{ $image['url'] }}" width="100" class="img-thumbnail">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                style="padding: 0.1rem 0.3rem; font-size: 0.7rem;"
                                                wire:click="removeExistingGalleryImage({{ $image['id'] }})"
                                                wire:confirm="আপনি কি এই ছবিটি মুছে ফেলতে চান?">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- নতুন গ্যালারি ছবি আপলোড করার ইনপুট --}}
                        <input type="file" wire:model="gallery_photos" class="form-control" multiple>
                        @if ($gallery_photos)
                            <div class="mt-3"><p>নতুন ছবির প্রিভিউ:</p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($gallery_photos as $photo)
                                        <img src="{{ $photo->temporaryUrl() }}" width="100" class="img-thumbnail">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- অন্যান্য ফিল্ড (CreateForm থেকে কপি করুন) --}}
                    {{-- ... --}}
                </div>
            </div>
        @endif

        {{-- নেভিগেশন বাটন (CreateForm থেকে কপি করুন, শুধু সাবমিট বাটনের লেখা পরিবর্তন করুন) --}}
        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
            <div>@if ($currentStep > 1)<button type="button" class="btn btn-secondary px-4" wire:click="previousStep">পূর্ববর্তী</button>@endif</div>
            <div class="ms-auto">
                @if ($currentStep < 4)
                    <button type="button" class="btn btn-primary px-4" wire:click="nextStep">পরবর্তী</button>
                @else
                    <button type="submit" class="btn btn-success px-5">
                        <i class="fas fa-check me-2"></i> আপডেট করুন
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
