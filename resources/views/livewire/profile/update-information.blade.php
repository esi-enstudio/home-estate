<form wire:submit.prevent="updateProfile">
    <h5 class="card-title mb-4">আপনার ব্যক্তিগত তথ্য</h5>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        <!-- Profile Picture -->
        <div class="col-12 mb-3">
            <label class="form-label">প্রোফাইল ছবি</label>
            <div class="d-flex align-items-center">
                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" class="rounded-circle me-3" width="80" height="80">
                @elseif($existingAvatar)
                    <img src="{{ Storage::url($existingAvatar) }}" class="rounded-circle me-3" width="80" height="80">
                @endif
                <input type="file" wire:model="avatar" id="avatar" class="form-control">
            </div>
            @error('avatar') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
        </div>

        <!-- Name -->
        <div class="col-md-12 mb-3">
            <label class="form-label">আপনার নাম</label>
            <input type="text" wire:model="name" class="form-control" @if($isVerified) disabled @endif>
            @if($isVerified)
                <small class="form-text text-muted">আপনার পরিচয় যাচাই সম্পন্ন হওয়ায় নাম পরিবর্তন করা যাবে না।</small>
            @endif
            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <!-- Designation -->
        <div class="col-md-6 mb-3">
            <label class="form-label">পদবী</label>
            <input type="text" wire:model="designation" class="form-control" placeholder="যেমন: ছাত্র, চাকরিজীবী">
        </div>

        <!-- Bio -->
        <div class="col-md-12 mb-3">
            <label class="form-label">আপনার সম্পর্কে</label>
            <textarea wire:model="bio" class="form-control" rows="3" placeholder="..."></textarea>
        </div>

        <!-- Social Links -->
        <h6 class="mt-3">সোশ্যাল লিংক</h6>
        <div class="col-md-6 mb-3">
            <label class="form-label"><i class="fab fa-facebook me-2"></i>ফেসবুক</label>
            <input type="url" wire:model="social_links.facebook" class="form-control" placeholder="https://facebook.com/username">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label"><i class="fab fa-twitter me-2"></i>টুইটার</label>
            <input type="url" wire:model="social_links.twitter" class="form-control" placeholder="https://twitter.com/username">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label"><i class="fab fa-linkedin me-2"></i>লিঙ্কডইন</label>
            <input type="url" wire:model="social_links.linkedin" class="form-control" placeholder="https://linkedin.com/in/username">
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">আপডেট করুন</button>
</form>
