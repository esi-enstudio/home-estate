<div class="card">
    <div class="card-header"><h5 class="mb-0">Enquire Us</h5></div>
    <div class="card-body">

        @if ($isSubmitted)
            {{-- ========== START: আকর্ষণীয় Success Message ========== --}}
            <div class="text-center p-4">
                <i class="material-icons-outlined text-success" style="font-size: 4rem;">check_circle</i>
                <h5 class="mt-3">Thank You!</h5>
                <p class="text-muted">Your enquiry has been sent successfully. The property owner will get in touch with you shortly.</p>
            </div>
            {{-- ========== END: Success Message ========== --}}
        @else
            {{-- ========== START: Livewire Form ========== --}}
            <form wire:submit.prevent="saveEnquiry">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror" placeholder="Your Name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" wire:model.lazy="email" class="form-control @error('email') is-invalid @enderror" placeholder="Your Email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" wire:model.lazy="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Your Phone Number">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Message</label>
                    <textarea wire:model.lazy="message" class="form-control @error('message') is-invalid @enderror" rows="4"></textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <button type="submit" class="btn btn-dark w-100 py-2 fs-14" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveEnquiry">
                            Submit Enquiry
                        </span>
                        <span wire:loading wire:target="saveEnquiry">
                            Submitting...
                        </span>
                    </button>
                </div>
            </form>
            {{-- ========== END: Livewire Form ========== --}}
        @endif

    </div>
</div>
