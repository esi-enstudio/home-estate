<div class="footer-widget footer-subscribe">
    <h5 class="footer-title">নিউজলেটার</h5>

    @if ($isSubscribed)
        {{-- ========== START: আকর্ষণীয় Success Message ========== --}}
        <div class="text-center p-4 bg-light rounded">
            <i class="material-icons-outlined text-success" style="font-size: 3rem;">check_circle</i>
            <h6 class="mt-2">সাবস্ক্রিপশনের জন্য ধন্যবাদ!</h6>
            <p class="fs-14 text-muted mb-0">আমাদের সেরা অফার এবং আপডেটগুলো এখন আপনার কাছেই প্রথম পৌঁছাবে।</p>
        </div>
        {{-- ========== END: Success Message ========== --}}
    @else
        {{-- ========== START: Livewire Form ========== --}}
        <div class="email-info">
            <h6>{{ $title }}</h6>
            <p>{{ $subtitle }}</p>
        </div>

        <form wire:submit.prevent="subscribe">
            <div class="d-flex align-items-start subscribe-wrap">
                <div class="input-group input-group-flat flex-grow-1">
                    <span class="input-group-text">
                        <i class="material-icons-outlined">email</i>
                    </span>
                    <input type="email" wire:model.lazy="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="আপনার ইমেইল ঠিকানা দিন">

                    {{-- ভ্যালিডেশন এরর মেসেজ দেখানোর জন্য --}}
                    @error('email')
                    <div class="invalid-feedback d-block w-100 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="subscribe">
                    <span wire:loading.remove wire:target="subscribe">
                        <i class="material-icons-outlined">send</i>
                    </span>
                    {{-- লোডিং স্পিনার --}}
                    <span wire:loading wire:target="subscribe">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </div>
        </form>
        {{-- ========== END: Livewire Form ========== --}}
    @endif
</div>
