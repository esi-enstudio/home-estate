<section class="faq-section-two">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title-2">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>সচরাচর জিজ্ঞাসিত <span>প্রশ্ন</span></h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>সাধারণ প্রশ্নগুলোর দ্রুত উত্তর জানুন</p>
        </div>
        <!-- Section Title End -->

        <!-- start row -->
        <div class="row align-items-center gy-4">
            <div class="col-lg-6" data-aos="fade-up">
                {{-- এই সেকশনের কন্টেন্ট স্ট্যাটিকই থাকছে --}}
                <div class="property-sec-img mt-0">
                    <div class="row g-3">
                        <div class="col-md-6 d-flex"><div class="flex-fill"><div><img src="{{ asset('assets/img/home-3/property/property-01.jpg') }}" class="img-fluid" alt="FAQ Image 1"></div></div></div>
                        <div class="col-md-6 d-flex"><div class="flex-fill"><div class="mb-3"><img src="{{ asset('assets/img/home-3/property/property-02.jpg') }}" class="img-fluid" alt="FAQ Image 2"></div><div><img src="{{ asset('assets/img/home-3/property/property-03.jpg') }}" alt="FAQ Image 3"></div></div></div>
                    </div>
                    <div class="rotate-div">
                        <div class="img-center-text">
                            <h3 class="mb-1 text-white">১০+</h3>
                            <p class="mb-0 fs-14 text-white text-center">বছরের <br> অভিজ্ঞতা</p>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->

            @if($faqs->isNotEmpty())
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="1500">
                    <div class="accordion accordions-items-seperate faq-accordion" id="faq-accordion">
                        @foreach($faqs as $faq)
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $faq->question }}
                                    </button>
                                </div>
                                <div id="accordion-{{ $faq->id }}" class="accordion-collapse collapse @if($loop->first) show @endif" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body">
                                        <p class="mb-0">{!! $faq->answer !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> <!-- end col -->
            @endif
        </div>
        <!-- end row -->

        {{-- Partner Slider অপরিবর্তিত থাকছে --}}
        <div class="partner-slider-two swiper">
            <div class="swiper-wrapper">
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-01.svg') }}" alt="">
                </div>
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-02.svg') }}" alt="">
                </div>
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-03.svg') }}" alt="">
                </div>
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-04.svg') }}" alt="">
                </div>
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-05.svg') }}" alt="">
                </div>
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-06.svg') }}" alt="">
                </div>
                <div class="partner-logo swiper-slide">
                    <img src="{{ asset('assets/img/icons/partner-03.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
