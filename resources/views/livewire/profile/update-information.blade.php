<form wire:submit.prevent="updateProfile">
    <h5 class="card-title mb-4">আপনার ব্যক্তিগত তথ্য</h5>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">আপনার নাম</label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">ইমেইল</label>
            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- প্রয়োজনে এখানে আরও ইনপুট ফিল্ড যোগ করুন --}}

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>আপডেট করুন</span>
                <span wire:loading>আপডেট হচ্ছে...</span>
            </button>
        </div>
    </div>
</form>
