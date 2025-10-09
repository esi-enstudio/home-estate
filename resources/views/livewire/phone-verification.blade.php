<div class="card">
    <div class="card-body">
        <h5 class="card-title">ফোন নম্বর ভেরিফিকেশন</h5>

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($user->phone_verified_at)
            <div class="text-success">
                <i class="material-icons-outlined">check_circle</i>
                আপনার ফোন নম্বর ({{ $user->phone }}) ইতোমধ্যে ভেরিফাই করা আছে।
            </div>
        @else
            @if(!$otpSent)
                <p>আপনার ফোন নম্বর ({{ $user->phone }}) ভেরিফাই করা নেই। ভেরিফাই করতে নিচের বাটনে ক্লিক করুন।</p>
                <button wire:click="sendOtp" wire:loading.attr="disabled" class="btn btn-primary">
                    <span wire:loading.remove>OTP পাঠান</span>
                    <span wire:loading>পাঠানো হচ্ছে...</span>
                </button>
            @else
                <p>আপনার ফোনে পাঠানো ৬-সংখ্যার কোডটি এখানে দিন:</p>
                <form wire:submit.prevent="verifyOtp">
                    <div class="mb-3">
                        <input type="text" wire:model.defer="otp" class="form-control @error('otp') is-invalid @enderror" placeholder="_ _ _ _ _ _">
                        @error('otp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-success">
                        <span wire:loading.remove>ভেরিফাই করুন</span>
                        <span wire:loading>ভেরিফাই হচ্ছে...</span>
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>
