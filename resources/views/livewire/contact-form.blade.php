<div class="contact-us-item-02">
    @if($isSubmitted)
        <div class="text-center p-5 bg-light rounded">
            <i class="material-icons-outlined text-success" style="font-size: 4rem;">check_circle</i>
            <h4 class="mt-3">ধন্যবাদ!</h4>
            <p class="text-muted">আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।</p>
        </div>
    @else
        <h2>যোগাযোগ করুন</h2>
        <form wire:submit.prevent="submitForm">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">আপনার নাম</label>
                    <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">ফোন নম্বর</label>
                    <input type="text" wire:model.defer="phone" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">ইমেইল</label>
                    <input type="email" wire:model.defer="email" class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">বিষয়</label>
                    <input type="text" wire:model.defer="subject" class="form-control @error('subject') is-invalid @enderror">
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">আপনার বার্তা</label>
                    <textarea wire:model.defer="message" class="form-control @error('message') is-invalid @enderror" rows="3" placeholder="..."></textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-lg btn-dark" wire:loading.attr="disabled">
                        <span wire:loading.remove>বার্তা পাঠান</span>
                        <span wire:loading>পাঠানো হচ্ছে...</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
