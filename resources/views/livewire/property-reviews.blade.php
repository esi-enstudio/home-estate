<div>
    {{-- ========== START: নতুন সাকসেস মেসেজ সেকশন ========== --}}
    <div x-data="{ show: @entangle('successMessage'), timer: null }"
         x-init="$watch('show', value => {
             if (value) {
                 clearTimeout(timer);
                 timer = setTimeout(() => {
                     show = false;
                     $wire.set('successMessage', null);
                 }, 5000); // ৫ সেকেন্ড পর মেসেজটি চলে যাবে
             }
         })"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="alert alert-success"
         style="display: none;" {{-- FOUC (Flash of Unstyled Content) এড়ানোর জন্য --}}
         role="alert">
        <span x-text="show"></span>
    </div>
    {{-- ========== END: নতুন সাকসেস মেসেজ সেকশন ========== --}}

    <div class="accordion-item mb-xl-0">
        <div class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-9" aria-expanded="true">Reviews</button>
        </div>
        <div id="accordion-9" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <div class="sub-head d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-16 fw-semibold"> Reviews ({{ $totalReviewsCount }}) </h6>

                    @auth
                        <a href="javascript:void(0);" class="btn btn-dark d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_review_modal">
                            <i class="material-icons-outlined me-1 fs-13">edit_note</i> আপনার মতামত দিন
                        </a>
                    @endauth

                    {{-- গেস্ট ব্যবহারকারীরা লগইন করার জন্য একটি লিঙ্ক দেখতে পাবে --}}
                    @guest
                        <a href="{{ route('filament.app.auth.login') }}" class="btn btn-dark d-flex align-items-center">
                            <i class="material-icons-outlined me-1 fs-13">lock</i> রিভিউ দিতে লগইন করুন
                        </a>
                    @endguest
                </div>

                <div class="row mb-3 gap-xl-0 gap-lg-0 gap-3">
                    <div class="col-lg-6 d-flex">
                        <div class="p-4 bg-light rounded text-center d-flex align-items-center justify-content-center flex-column flex-fill">
                            <h6 class="fs-16 fw-medium mb-3"> Customer Reviews & Ratings </h6>
                            <div class="mb-3">
                                <h2 class="mb-1"> {{ number_format($averageRating, 1) }} <span class="fs-16 text-body fw-normal"> / 5.0</span> </h2>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="material-icons-outlined fs-14 text-warning">{{ $i <= round($averageRating) ? 'star' : 'star_border' }}</i>
                                    @endfor
                                </div>
                            </div>
                            <p class="mb-0 fs-14"> Based On {{ $totalReviewsCount }} Reviews </p>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex">
                        <div class="card shadow-none review-progress flex-fill mb-0">
                            <div class="card-body">
                                @foreach($ratingStats as $star => $stats)
                                    <div class="progress-lvl @if($loop->last) mb-0 @else mb-2 @endif">
                                        <p>{{ $star }} Star Ratings</p>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['percentage'] }}%"></div>
                                        </div>
                                        <p>{{ $stats['count'] }}</p>
                                    </div>
                                @endforeach
                            </div></div>
                    </div>
                </div>

                @forelse($reviews as $review)
                        <div x-data="{ openReplyBox: false }"
                             wire:key="review-{{ $review->id }}"
                             {{--
                                Livewire থেকে 'reply-submitted-successfully' ইভেন্টটি এলে,
                                AlpineJS 'openReplyBox'-এর মান 'false' করে দেবে।
                             --}}
                             x-on:reply-submitted-successfully.window="openReplyBox = false"
                             class="card shadow-none review-items
                             @if($review->replies->where('status', 'approved')->isNotEmpty() || !$loop->last)mb-4 @endif"
                             style="padding: 20px;">

                        <div class="card-body">
                            <div class="d-flex align-center flex-wrap justify-content-between gap-1 mb-2">
                                <div class="mb-2 d-flex align-center gap-2 flex-wrap">
                                <div class="avatar avatar-lg">
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($review->user->avatar_url) ?? 'https://placehold.co/100' }}"
                                        alt="{{ $review->user->name }}"
                                        class="img-fluid rounded-circle"
                                    >
                                </div>

                                <div>
                                    <h6 class="fs-16 fw-medium mb-1 d-flex align-items-center gap-2">
                                        {{ $review->user->name }}

                                        {{-- ========== START: Owner Badge কন্ডিশন ========== --}}
                                        @if ($review->user_id === $property->user_id)
                                            <span class="badge bg-primary">Owner</span>
                                        @endif
                                        {{-- ========== END: Owner Badge কন্ডিশন ========== --}}
                                    </h6>

                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <p class="fs-14 mb-0 text-body">{{ $review->created_at->diffForHumans() }}</p>
                                        <i class="fa-solid fa-circle text-body"></i>
                                        <div class="d-flex align-items-center justify-content-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="material-icons-outlined text-warning fs-14">{{ $i <= $review->rating ? 'star' : 'star_border' }}</i>
                                            @endfor
                                        </div>
                                        <p class="fs-14 mb-0 text-body">{{ $review->title }}</p>
                                    </div>
                                </div>
                            </div>
                                @auth {{-- শুধুমাত্র লগইন করা ব্যবহারকারীরাই রিপ্লাই বাটন দেখতে পাবে --}}
{{--                                <a href="javascript:void(0);" @click="openReplyBox = !openReplyBox" class="btn d-inline-flex align-items-center fs-13 fw-semibold reply-btn">--}}
{{--                                    <i class="material-icons-outlined text-dark me-1">repeat</i> Reply--}}
{{--                                </a>--}}
                                <div class="d-flex gap-2">
                                    {{-- শুধুমাত্র लेखक (author) এই বাটনগুলো দেখতে পাবে --}}
                                    @if(auth()->id() === $review->user_id)
                                        <a href="javascript:void(0);" wire:click="edit({{ $review->id }})"
                                           class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn"
                                           title="Edit">
                                            <i class="material-icons-outlined fs-16">edit</i>
                                        </a>

                                        <a href="javascript:void(0);" wire:click="delete({{ $review->id }})"
                                           wire:confirm="আপনি কি নিশ্চিতভাবে এটি মুছে ফেলতে চান?"
                                           class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn bg-danger text-white"
                                           title="Delete">
                                            <i class="material-icons-outlined fs-16">delete</i>
                                        </a>
                                    @endif
                                    <a href="javascript:void(0);" @click="$dispatch('open-reply-box', { reviewId: {{ $review->id }} })"
                                       class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn">
                                        <i class="material-icons-outlined text-dark me-1">repeat</i> Reply
                                    </a>
                                </div>
                                @endauth
                            </div>

                            {{-- === START: ইন-লাইন এডিটিং UI === --}}
                            @if($editingReviewId === $review->id)
                                <div>
                                    <textarea wire:model.defer="editingReviewBody" class="form-control mb-2" rows="3"></textarea>
                                    @error('editingReviewBody') <span class="text-danger fs-14">{{ $message }}</span> @enderror
                                    <div class="d-flex justify-content-end gap-2">
                                        <button wire:click="cancelEdit" class="btn btn-sm btn-secondary">বাতিল</button>
                                        <button wire:click="update" class="btn btn-sm btn-primary">সেভ করুন</button>
                                    </div>
                                </div>
                            @else
                                <p class="mb-2 text-body">{{ $review->body }}</p>
                            @endif
                            {{-- === END === --}}

                            {{-- ========== START: ডাইনামিক রিঅ্যাকশন সেকশন ========== --}}
                            <div class="d-flex align-items-center gap-3">
                                <a href="#" wire:click.prevent="toggleReaction({{ $review->id }}, 'like')" class="mb-0 d-flex align-items-center fs-14 text-decoration-none @if($review->authUserReaction?->type === 'like') text-primary @else text-body @endif">
                                    <i class="material-icons-outlined me-1 fs-14">thumb_up</i> {{ $review->likes_count }}
                                </a>

                                <a href="#" wire:click.prevent="toggleReaction({{ $review->id }}, 'dislike')" class="mb-0 d-flex align-items-center fs-14 text-decoration-none @if($review->authUserReaction?->type === 'dislike') text-primary @else text-body @endif">
                                    <i class="material-icons-outlined me-1 fs-14">thumb_down</i> {{ $review->dislikes_count }}
                                </a>

                                <a href="#" wire:click.prevent="toggleReaction({{ $review->id }}, 'favorite')" class="mb-0 d-flex align-items-center fs-14 text-decoration-none @if($review->authUserReaction?->type === 'favorite') text-danger @else text-body @endif">
                                    <i class="material-icons-outlined me-1 fs-14">favorite_border</i> {{ $review->favorites_count }}
                                </a>
                            </div>
                            {{-- ========== END: ডাইনামিক রিঅ্যাকশন সেকশন ========== --}}
                        </div>

                        {{-- === START: কলাপসিবল রিপ্লাই ফর্ম === --}}
                        <div x-show="openReplyBox" x-transition class="card-body border-top pt-3">
                            <form wire:submit.prevent="submitReply({{ $review->id }})">
                                <div class="mb-2">
                                    <textarea wire:model.defer="replyBody" class="form-control" rows="3" placeholder="আপনার উত্তর লিখুন..."></textarea>
                                    @error('replyBody') <span class="text-danger fs-14">{{ $message }}</span> @enderror
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="openReplyBox = false" class="btn btn-sm btn-secondary">বাতিল</button>
                                    <button type="submit" class="btn btn-sm btn-primary">উত্তর দিন</button>
                                </div>
                            </form>
                        </div>
                        {{-- === END === --}}

                        {{-- ========== START: রিপ্লাই দেখানোর জন্য নতুন এবং পরিবর্তিত সেকশন ========== --}}
                        {{-- 'children' এর পরিবর্তে সঠিক রিলেশনশিপের নাম 'replies' ব্যবহার করা হচ্ছে --}}
                        @if ($review->replies->where('status', 'approved')->isNotEmpty())
                            @foreach($review->replies->where('status', 'approved') as $reply)
                                <!-- Start reply item-->
                                    <div wire:key="reply-{{ $reply->id }}" class="card shadow-none review-items bg-light border-0 mb-0 ms-lg-5 ms-md-5 ms-3 mt-3">
                                    <div class="card-body">
                                        <div class="d-flex align-center flex-wrap justify-content-between gap-1 mb-2">
                                            <div class="d-flex align-center gap-2 flex-wrap">
                                                <div class="avatar avatar-lg">
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($reply->user->avatar_url) ?? 'https://placehold.co/100' }}" alt="{{ $reply->user->name }}" class="img-fluid rounded-circle">
                                                </div>
                                                <div>
                                                    <h6 class="fs-16 fw-medium mb-1 d-flex align-items-center gap-2">
                                                        {{ $reply->user->name }}

                                                        {{-- === START: ডাইনামিক ব্যাজ লজিক === --}}
                                                        @if ($reply->user_id === $property->user_id)
                                                            <span class="badge bg-primary">Owner</span>
                                                        @elseif ($reply->user->hasRole('super_admin'))
                                                            <span class="badge bg-danger">Admin</span>
                                                        @endif
                                                        {{-- === END === --}}

                                                    </h6>
                                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                                        <p class="fs-14 mb-0 text-body">{{ $reply->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <a href="javascript:void(0);" class="btn d-inline-flex align-items-center fs-13 fw-semibold reply-btn">--}}
{{--                                                <i class="material-icons-outlined text-dark me-1">repeat</i>--}}
{{--                                                Reply--}}
{{--                                            </a>--}}
                                            {{-- শুধুমাত্র রিপ্লাইয়ের लेखक (author) এই বাটনগুলো দেখতে পাবে --}}
                                            @if(auth()->check() && auth()->id() === $reply->user_id)
                                                <div class="d-flex gap-2">
                                                    <a href="javascript:void(0);" wire:click="edit({{ $reply->id }})" class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn" title="Edit">
                                                        <i class="material-icons-outlined fs-16">edit</i>
                                                    </a>

                                                    <a href="javascript:void(0);" wire:click="delete({{ $reply->id }})"
                                                       wire:confirm="আপনি কি নিশ্চিতভাবে এটি মুছে ফেলতে চান?"
                                                       class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn bg-danger text-white"
                                                       title="Delete">
                                                        <i class="material-icons-outlined fs-16">delete</i>
                                                    </a>
                                                </div>
                                            @endif

                                        </div>

                                        @if($editingReviewId === $reply->id)
                                            <div>
                                                <x-filament::input wire:model.defer="editingReviewBody" class="form-control mb-2"></x-filament::input>
                                                @error('editingReviewBody') <span class="text-danger fs-14">{{ $message }}</span> @enderror
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button wire:click="cancelEdit" class="btn btn-sm btn-secondary">বাতিল</button>
                                                    <button wire:click="update" class="btn btn-sm btn-primary">সেভ করুন</button>
                                                </div>
                                            </div>
                                        @else
                                            <p class="mb-2 text-body">{{ $reply->body }}</p>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        @endif
                        {{-- ========== END: রিপ্লাই দেখানোর জন্য নতুন সেকশন ========== --}}
                    </div>
                @empty
                    <p class="text-center my-4">No reviews yet for this property.</p>
                @endforelse

                @if($hasMorePages)
                    <div class="text-center">
                        <button wire:click="loadMore" wire:loading.attr="disabled" class="btn btn-dark d-inline-flex align-center gap-1 review-btn">
                            <span wire:loading.remove>See More Reviews</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Add Review Modal -->
    <div wire:ignore.self id="add_review_modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="submitReview">
                    <div class="modal-header">
                        <h4 class="text-dark modal-title fw-bold">আপনার মতামত দিন</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- ========== START: এরর মেসেজ দেখানোর জন্য ========== --}}
                        @if(session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        {{-- ========== END: এরর মেসেজ দেখানোর জন্য ========== --}}

                        <div class="mb-3">
                            <label class="form-label">Your Rating</label>

                            <div class="selection-wrap">
                                <div class="d-inline-block">
                                    <div class="rating-selction">
                                        <input type="radio" wire:model="rating" value="5" id="rating5">
                                        <label for="rating5"><i class="fa-solid fa-star"></i></label>
                                        <input type="radio" wire:model="rating" value="4" id="rating4">
                                        <label for="rating4"><i class="fa-solid fa-star"></i></label>
                                        <input type="radio" wire:model="rating" value="3" id="rating3">
                                        <label for="rating3"><i class="fa-solid fa-star"></i></label>
                                        <input type="radio" wire:model="rating" value="2" id="rating2">
                                        <label for="rating2"><i class="fa-solid fa-star"></i></label>
                                        <input type="radio" wire:model="rating" value="1" id="rating1">
                                        <label for="rating1"><i class="fa-solid fa-star"></i></label>
                                    </div>
                                </div>
                            </div>
                            @error('rating') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review Title</label>
                            <input type="text" wire:model.defer="title" class="form-control" placeholder="e.g., A Wonderful Place">
                            @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Write your review</label>
                            <textarea wire:model.defer="body" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                            @error('body') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-lg btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Submit Review</span>
                            <span wire:loading>Submitting...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // রিভিউ মডাল বন্ধ করার জন্য ইভেন্ট লিসেনার
        window.addEventListener('close-review-modal', event => {
            // Bootstrap 5 এর JS API ব্যবহার করে মডালটি খুঁজে বের করে বন্ধ করা হচ্ছে
            var reviewModalEl = document.getElementById('add_review_modal');
            var modal = bootstrap.Modal.getInstance(reviewModalEl);

            if (modal) {
                modal.hide();
            }
        });
    </script>
@endpush
