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
                    <x-review-item :item="$review" :property="$property" :editingReviewId="$editingReviewId" />
                @empty
                    <p class="text-center my-4">এই প্রপার্টির জন্য এখনো কোনো রিভিউ নেই।</p>
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
