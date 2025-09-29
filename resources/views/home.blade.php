@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <!-- Home Banner Section Start -->
    <section class="home-banner-two">

        <div>
            <div class="banner-img-right" data-aos="fade-down" data-aos-duration="1000">
                <img src="{{ asset('assets/img/section-bg/banner-bg-02.png') }}" alt="">
            </div>
            <div>
                <img src="{{ asset('assets/img/bg/banner-round-bg.svg') }}" class="banner-round" alt="">
            </div>
            <div>
                <img src="{{ asset('assets/img/bg/banner-shape.svg') }}" class="banner-shape" alt="">
            </div>
        </div>

        <div class="container">

            <!-- start row -->
            <div class="row">

                <div class="col-lg-5">
                    <div class="banner-users d-flex align-items-center flex-wrap gap-2 mb-3">
                        <div class="avatar-list-stacked">
                            <span class="avatar avatar-md rounded-circle border-0"><img src="{{ asset('assets/img/users/user-01.jpg') }}" class="img-fluid rounded-circle" alt="Img"></span>
                            <span class="avatar avatar-md rounded-circle border-0"><img src="{{ asset('assets/img/users/user-02.jpg') }}" class="img-fluid rounded-circle" alt="Img"></span>
                            <span class="avatar avatar-md rounded-circle border-0"><img src="{{ asset('assets/img/users/user-03.jpg') }}" class="img-fluid rounded-circle" alt="Img"></span>
                            <span class="avatar avatar-md rounded-circle border-0"><img src="{{ asset('assets/img/users/user-04.jpg') }}" class="img-fluid rounded-circle" alt="Img"></span>
                        </div>

                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0 me-2 text-white fw-semibold fs-14">Ratings 5.0</h6>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                            </div>
                            <p class="mb-0 text-white fs-13">Trusted By Client around the World</p>
                        </div>
                    </div>
                    <div class="banner-title aos" data-aos="fade-up">
                        <h1>Find your <span>Next Home</span> Away from Home.</h1>
                        <p>Say goodbye to the complexities and headaches of traditional solutions and embrace a streamlined approach.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="buy-property-grid.html" class="btn btn-primary btn-lg d-inline-flex align-items-center me-3"><i class="material-icons-outlined me-2">shopping_basket</i>Buy Property</a>

                        <a href="rent-property-grid.html" class="btn btn-white btn-lg d-inline-flex align-items-center"><i class="material-icons-outlined me-2">king_bed</i>Rent Property</a>
                    </div>
                </div> <!-- end col -->

            </div>
            <!-- end row -->

        </div>
    </section>
    <!-- Home Banner Section End -->

    <!-- Search Start -->
    <div class="home-search-2">
        <div class="container">
            <form action="https://dreamsestate.dreamstechnologies.com/html/buy-property-grid-sidebar.html">
                <div class="d-flex align-items-end flex-wrap flex-md-nowrap gap-3">
                    <div class="flex-fill select-field">
                        <label class="form-label">Buy / Sell</label>
                        <select class="select">
                            <option>Select</option>
                            <option>Buy</option>
                            <option>Sell</option>
                        </select>
                    </div>
                    <div class="flex-fill select-field">
                        <label class="form-label">Type of Property</label>
                        <select class="select">
                            <option>Select</option>
                            <option>Buy Property</option>
                            <option>Rent Property</option>
                        </select>
                    </div>
                    <div class="flex-fill select-field">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="flex-fill select-field">
                        <label class="form-label">Min Price</label>
                        <input type="text" class="form-control" placeholder="$">
                    </div>
                    <div class="flex-fill select-field">
                        <label class="form-label">Max Price</label>
                        <input type="text" class="form-control" placeholder="$">
                    </div>
                    <div class="select-btn">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons-outlined">search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- About Us Section Start -->
    <livewire:homepage-stats/>
    <!-- About Us Section End -->

    <!-- Property Type Section Start -->
    <livewire:property-types-section/>
    <!-- Property Type Section End -->

    <!-- Support Section Start -->
    <section class="support-section">
        <div class="horizontal-slide d-flex" data-direction="right" data-speed="slow">
            <div class="slide-list d-flex">
                <div class="support-item">
                    <h5>Personalized Itineraries</h5>
                </div>
                <div class="support-item">
                    <h5>Comprehensive Planning</h5>
                </div>
                <div class="support-item">
                    <h5>Expert Guidance</h5>
                </div>
                <div class="support-item">
                    <h5>Local Experience</h5>
                </div>
                <div class="support-item">
                    <h5>Customer Support</h5>
                </div>
                <div class="support-item">
                    <h5>Sustainability Efforts</h5>
                </div>
                <div class="support-item">
                    <h5>Multiple Regions</h5>
                </div>
            </div>
        </div>
    </section>
    <!-- Support Section End -->

    <!-- Popular Listing Section Start -->
    <livewire:popular-listings/>
    <!-- Popular Listing Section End -->

    <!-- Exclusive Benifits Section Start -->
    <livewire:exclusive-benefits/>
    <!-- Exclusive Benifits Section End -->

    <!-- Feature Location Section Start -->
    <livewire:featured-locations-section/>
    <!-- Feature Location Section End -->

    <!-- Work Section Start -->
    <livewire:how-it-works-section/>
    <!-- Work Section End -->

    <!-- Customer Review Section Start -->
    <livewire:customer-testimonials/>
    <!-- Customer Review Section End -->

    <!-- FAQ Section Start -->
    <section class="faq-section-two">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Frequently Asked <span> Questions</span></h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Quick Answers to Common Questions</p>
            </div>
            <!-- Section Title End -->

            <!-- start row -->
            <div class="row align-items-center gy-4">

                <div class="col-lg-6" data-aos="fade-up">
                    <div class="property-sec-img mt-0">
                        <div class="row g-3">
                            <div class="col-md-6 d-flex">
                                <div class="flex-fill">
                                    <div><img src="assets/img/home-3/property/property-01.jpg" class="img-fluid" alt=""></div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="flex-fill">
                                    <div class="mb-3"><img src="assets/img/home-3/property/property-02.jpg" class="img-fluid" alt=""></div>
                                    <div><img src="assets/img/home-3/property/property-03.jpg" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="rotate-div">
                            <div class="img-center-text">
                                <h3 class="mb-1 text-white">10+</h3>
                                <p class="mb-0 fs-14 text-white text-center">Years of <br> Experience</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="1500">
                    <div class="accordion accordions-items-seperate faq-accordion" id="faq-accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-1" aria-expanded="true">
                                    Does offer free cancellation for a full refund?
                                </button>
                            </div>
                            <div id="accordion-1" class="accordion-collapse collapse show" data-bs-parent="#faq-accordion">
                                <div class="accordion-body">
                                    <p class="mb-0">Does have fully refundable room rates available to book on our site. If you’ve booked a fully
                                        refundable room rate, this can be cancelled up to a few days before check-in depending on the
                                        property's cancellation policy.
                                        Just make sure to check this property's cancellation policy for the exact terms and conditions.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-2" aria-expanded="false">
                                    Is there a pool?
                                </button>
                            </div>
                            <div id="accordion-2" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                                <div class="accordion-body">
                                    <p class="mb-0">Yes, there is a pool available for guests, providing a perfect place to relax, unwind, and enjoy some leisure time during their stay.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-3" aria-expanded="false">
                                    Are pets allowed?
                                </button>
                            </div>
                            <div id="accordion-3" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                                <div class="accordion-body">
                                    <p class="mb-0">Yes, pets are allowed, and we welcome your furry friends to stay with you, ensuring a comfortable experience for both you and your pets.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-4" aria-expanded="false">
                                    Is airport shuttle service offered?
                                </button>
                            </div>
                            <div id="accordion-4" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                                <div class="accordion-body">
                                    <p class="mb-0">Yes, airport shuttle service is offered to provide convenient and reliable transportation for our guests between the airport and their destination, ensuring a smooth and stress-free travel experience.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-5" aria-expanded="false">
                                    What are the check-in and check-out times?
                                </button>
                            </div>
                            <div id="accordion-5" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                                <div class="accordion-body">
                                    <p class="mb-0">Check-in is typically from 12:00 PM, and check-out is usually by 11:00 AM to ensure a smooth transition for all guests.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
            <!-- end row -->

            <div class="partner-slider-two swiper">
                <div class="swiper-wrapper">
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-01.svg" alt="">
                    </div>
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-02.svg" alt="">
                    </div>
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-03.svg" alt="">
                    </div>
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-04.svg" alt="">
                    </div>
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-05.svg" alt="">
                    </div>
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-06.svg" alt="">
                    </div>
                    <div class="partner-logo swiper-slide">
                        <img src="assets/img/icons/partner-03.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section End -->

    <!-- Blog Section Start -->
    <section class="blogs-section">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2" data-aos="fade-up" data-aos-duration="1000">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Latest <span> Blogs</span></h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Explore our featured blog posts on premium properties for sales & rents</p>
            </div>
            <!-- Section Title End -->

            <!-- end col -->
            <div class="row justify-content-center">
                <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="blog-item-two">
                        <div class="blog-content">
                            <div class="blog-img">
                                <a href="blog-details.html"><img src="assets/img/blogs/blog-img-22.jpg" class="img-fluid" alt=""></a>
                            </div>
                            <div class="position-absolute top-0 start-0 p-3 z-1">
                                <div class="blog-date">
                                    <h6 class="mb-0">10</h6>
                                    <span>Jul</span>
                                </div>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-3 text-center z-1">
                                <span class="badge bg-danger mb-2">Bookings</span>
                                <h5 class="mb-0"><a href="blog-details.html">Top 10 Tips for First-Time Homebuyers in 2025</a></h5>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="1500">
                    <div class="blog-item-two">
                        <div class="blog-content">
                            <div class="blog-img">
                                <a href="blog-details.html"><img src="assets/img/blogs/blog-img-23.jpg" class="img-fluid" alt=""></a>
                            </div>
                            <div class="position-absolute top-0 start-0 p-3 z-1">
                                <div class="blog-date">
                                    <h6 class="mb-0">10</h6>
                                    <span>Jul</span>
                                </div>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-3 text-center z-1">
                                <span class="badge bg-danger mb-2">Rental</span>
                                <h5 class="mb-0"><a href="blog-details.html">First-Time Buyer’s Guide: What to Expect in 2025</a></h5>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="2000">
                    <div class="blog-item-two">
                        <div class="blog-content">
                            <div class="blog-img">
                                <a href="blog-details.html"><img src="assets/img/blogs/blog-img-24.jpg" class="img-fluid" alt=""></a>
                            </div>
                            <div class="position-absolute top-0 start-0 p-3 z-1">
                                <div class="blog-date">
                                    <h6 class="mb-0">10</h6>
                                    <span>Jul</span>
                                </div>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-3 text-center z-1">
                                <span class="badge bg-danger mb-2">Tips</span>
                                <h5 class="mb-0"><a href="blog-details.html">Top Property Investment Trends in 2025</a></h5>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>

        </div>
    </section>
    <!-- Blog Section Start -->
@endsection
