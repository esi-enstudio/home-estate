<div class="card shadow-none mb-0">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0">এই লেখাটি কি আপনার জন্য সহায়ক ছিল?</h6>

            @if(session()->has('vote_message'))
                <div class="alert alert-success py-2 px-3 mb-0">{{ session('vote_message') }}</div>
            @else
                <div>
                    <p class="mb-0">{{ $helpfulCount + $unhelpfulCount }} জনের মধ্যে {{ $helpfulCount }} জন এটি সহায়ক মনে করেছেন</p>
                </div>
                <div class="d-flex align-items-center">
                    <button wire:click="vote('yes')" class="btn btn-sm btn-white d-inline-flex align-items-center me-2" @if($hasVoted) disabled @endif>
                        <i class="material-icons-outlined me-1">thumb_up</i>হ্যাঁ
                    </button>
                    <button wire:click="vote('no')" class="btn btn-sm btn-white d-inline-flex align-items-center" @if($hasVoted) disabled @endif>
                        <i class="material-icons-outlined me-1">thumb_down</i>না
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
