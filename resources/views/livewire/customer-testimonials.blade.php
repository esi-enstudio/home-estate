<section class="customer-review-section">
    @if($testimonials->isNotEmpty())
    <div class="container">
        <!-- Section Title Start -->
        <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>সফলতার গল্পগুলো তাদের <span>মুখেই শুনুন</span></h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>আমাদের সন্তুষ্ট গ্রাহকদের অভিজ্ঞতা জানুন</p>
        </div>
        <!-- Section Title End -->


        <div class="review-slider swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                    <div class="review-item-two swiper-slide">
                        <span class="mb-3 d-block"><img src="{{ asset('assets/img/icons/quote-down.svg') }}" class="w-auto m-auto" alt="উক্তি"></span>
                        <div class="review-content">
                            <p class="mb-2">"{{ Str::limit($testimonial->body, 120) }}"</p>
                            <div class="d-flex align-items-center justify-content-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="material-icons-outlined text-warning">{{ $i <= $testimonial->rating ? 'star' : 'star_border' }}</i>
                                @endfor
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2">
                                <img src="{{ $testimonial->user->avatar_url ?? 'https://placehold.co/100' }}" alt="{{ $testimonial->user->name }}">
                            </a>
                            <div>
                                <h6 class="fs-16 fw-semibold mb-0"><a href="javascript:void(0)">{{ $testimonial->user->name }}</a></h6>
                                <span class="fs-14">সম্মানিত গ্রাহক</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="text-center">
                <div class="d-inline-flex align-items-center flex-wrap gap-2 review-users">
                    <div class="avatar-list-stacked">
                        @foreach($testimonials->take(4) as $testimonial)
                            <span class="avatar avatar-md rounded-circle border-0">
                                <img src="{{ $testimonial->user->avatar_url ?? 'https://placehold.co/100' }}" class="img-fluid rounded-circle" alt="User">
                            </span>
                        @endforeach
                    </div>
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <h6 class="mb-0 me-2 text-white fw-semibold fs-14">গড় রেটিং {{ number_format($overallRating, 1) }}</h6>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="material-icons-outlined text-warning fs-14">{{ $i <= round($overallRating) ? 'star' : 'star_border' }}</i>
                            @endfor
                        </div>
                        <p class="mb-0 text-white fs-13">{{ $totalReviews }}+ এর বেশি গ্রাহকের বিশ্বাস</p>
                    </div>
                </div>
            </div>

    </div>
    @endif
</section>


@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // এই কোডটি নিশ্চিত করে যে Livewire লোড হওয়ার পর Swiper চালু হবে
            var reviewSlider = new Swiper(".review-slider", {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1200: {
                        slidesPerView: 3,
                    }
                }
            });
        });
    </script>
@endpush
