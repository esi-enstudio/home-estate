<form wire:submit.prevent="updatePassword">
    <h5 class="card-title mb-4">আপনার পাসওয়ার্ড পরিবর্তন করুন</h5>
    @if (session('password_status'))
        <div class="alert alert-success">{{ session('password_status') }}</div>
    @endif

    <div class="mb-3">
        <label for="current_password" class="form-label">বর্তমান পাসওয়ার্ড</label>
        <input type="password" wire:model="current_password" id="current_password" class="form-control">
        @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">নতুন পাসওয়ার্ড</label>
        <input type="password" wire:model="password" id="password" class="form-control">
        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
        <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">পাসওয়ার্ড পরিবর্তন</button>
</form>
