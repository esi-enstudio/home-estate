<div>
    <h3 class="mb-4">আপনার পরিচয়পত্র যাচাইয়ের জন্য জমা দিন</h3>

    {{-- পূর্ববর্তী সাবমিশনের স্ট্যাটাস দেখানোর জন্য --}}
    @php
        // 'form-submitted' ইভেন্টের পর স্ট্যাটাস রিফ্রেশ করার জন্য
        $latestVerification = auth()->user()->identityVerifications()->latest()->first();
    @endphp

    @if(session()->has('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if($latestVerification)
        <div class="mb-4 p-3 rounded
            @if($latestVerification->status === 'pending') alert alert-warning
            @elseif($latestVerification->status === 'approved') alert alert-success
            @elseif($latestVerification->status === 'rejected') alert alert-danger
            @endif"
             role="alert">

            <h6 class="alert-heading">আপনার সর্বশেষ আবেদনের অবস্থা: <span class="text-capitalize fw-bold">{{ $latestVerification->status }}</span></h6>
            <hr>

            @if($latestVerification->status === 'rejected')
                <p class="mb-0"><strong>বাতিলের কারণ:</strong> {{ $latestVerification->rejection_reason }}</p>
                <p class="mt-2">অনুগ্রহ করে সঠিক তথ্য দিয়ে পুনরায় আবেদন করুন।</p>
            @elseif($latestVerification->status === 'pending')
                <p class="mb-0">আপনার আবেদনটি পর্যালোচনার অধীনে আছে।</p>
            @elseif($latestVerification->status === 'approved')
                <p class="mb-0">আপনার পরিচয় সফলভাবে যাচাই করা হয়েছে।</p>
            @endif
        </div>
    @endif


    {{-- যদি ভেরিফিকেশন 'approved' না হয়ে থাকে, তাহলে ফর্মটি দেখানো হবে --}}
    @if(!$latestVerification || $latestVerification->status !== 'approved')
        <form wire:submit.prevent="submitVerificationRequest">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_type" class="form-label">আইডির ধরন <span class="text-danger">*</span></label>
                    {{-- এখানে wire:model.live ব্যবহার করা হয়েছে যাতে সিলেক্ট করার সাথে সাথেই আপডেট হয় --}}
                    <select id="id_type" wire:model.live="id_type" class="form-select @error('id_type') is-invalid @enderror">
                        <option value="">-- ধরন নির্বাচন করুন --</option>
                        <option value="NID">জাতীয় পরিচয় পত্র (NID)</option>
                        <option value="Passport">পাসপোর্ট</option>
                        <option value="Driving License">ড্রাইভিং লাইসেন্স</option>
                        <option value="Birth Certificate">জন্ম সনদ</option>
                    </select>
                    @error('id_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- যখন কোনো একটি ধরন সিলেক্ট করা হবে, তখনই বাকি ফিল্ডগুলো দেখানো হবে --}}
                @if($id_type)
                    <div class="col-md-6 mb-3">
                        {{-- লেবেল এবং প্লেসহোল্ডার এখন ডাইনামিক --}}
                        <label for="id_number" class="form-label">{{ $idNumberLabel }} <span class="text-danger">*</span></label>
                        <input type="text" id="id_number" wire:model="id_number" class="form-control @error('id_number') is-invalid @enderror" placeholder="{{ $idNumberPlaceholder }}">
                        @error('id_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        {{-- ছবির লেবেল এখন ডাইনামিক --}}
                        <label for="front_image" class="form-label">{{ $frontImageLabel }} <span class="text-danger">*</span></label>
                        <input type="file" id="front_image" wire:model="front_image" class="form-control @error('front_image') is-invalid @enderror">
                        @error('front_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div wire:loading wire:target="front_image" class="text-muted mt-1">আপলোড হচ্ছে...</div>

                        @if ($front_image && !$errors->has('front_image'))
                            <div class="mt-2">
                                <img src="{{ $front_image->temporaryUrl() }}" width="150" alt="Front Image Preview" class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    {{-- শুধুমাত্র NID সিলেক্ট করা হলেই এই ফিল্ডটি দেখা যাবে --}}
                    @if ($id_type === 'NID')
                        <div class="col-md-6 mb-3">
                            <label for="back_image" class="form-label">NID কার্ডের পেছনের দিকের ছবি <span class="text-danger">*</span></label>
                            <input type="file" id="back_image" wire:model="back_image" class="form-control @error('back_image') is-invalid @enderror">
                            @error('back_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div wire:loading wire:target="back_image" class="text-muted mt-1">আপলোড হচ্ছে...</div>

                            @if ($back_image && !$errors->has('back_image'))
                                <div class="mt-2">
                                    <img src="{{ $back_image->temporaryUrl() }}" width="150" alt="Back Image Preview" class="img-thumbnail">
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitVerificationRequest">
                            আবেদন জমা দিন
                        </span>
                            <span wire:loading wire:target="submitVerificationRequest">
                            জমা হচ্ছে...
                        </span>
                        </button>
                    </div>
                @endif
            </div>
        </form>
    @endif
</div>
