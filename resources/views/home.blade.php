@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <!-- Home Banner Section Start -->
    <section class="home-banner-two">

        <div>
            <div class="banner-img-right" data-aos="fade-down" data-aos-duration="1000">
                <img src="assets/img/section-bg/banner-bg-02.png" alt="">
            </div>
            <div>
                <img src="assets/img/bg/banner-round-bg.svg" class="banner-round" alt="">
            </div>
            <div>
                <img src="assets/img/bg/banner-shape.svg" class="banner-shape" alt="">
            </div>
        </div>

        <div class="container">

            <!-- start row -->
            <div class="row">

                <div class="col-lg-5">
                    <div class="banner-users d-flex align-items-center flex-wrap gap-2 mb-3">
                        <div class="avatar-list-stacked">
                            <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-01.jpg" class="img-fluid rounded-circle" alt="Img"></span>
                            <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-02.jpg" class="img-fluid rounded-circle" alt="Img"></span>
                            <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-03.jpg" class="img-fluid rounded-circle" alt="Img"></span>
                            <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-04.jpg" class="img-fluid rounded-circle" alt="Img"></span>
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
    <section class="about-us-section-2">
        <div class="container">

            <!-- start row -->
            <div class="row align-items-center gy-4">

                <div class="col-lg-6">

                    <!-- Section Title Start -->
                    <div class="title-head" data-aos="fade-up" data-aos-duration="500">
                        <span class="badge bg-secondary mb-2">About Us</span>
                        <h2 class="mb-2">We make property discovery simple, transparent, and stress-free.</h2>
                        <p>These hand-picked locations highlight our strongest presence, fastest response times, and highest customer satisfaction. Whether you're looking for expert professionals or trusted services nearby, explore what's available in your area.</p>
                        <div class="d-flex align-items-center">
                            <a href="add-property-buy.html" class="btn btn-dark btn-lg me-3">Start Post Your Property</a>
                            <a href="contact-us.html" class="btn btn-primary btn-lg">Contact Us</a>
                        </div>
                    </div>
                    <!-- Section Title End -->

                </div> <!-- end col -->

                <div class="col-lg-6">
                    <div class="position-relative" data-aos="fade-up" data-aos-duration="1000">
                        <div><img src="assets/img/section-bg/section-bg-01.png" class="img-fluid" alt=""></div>
                        <div class="position-absolute end-0 top-0">
                            <img src="assets/img/bg/line-01.svg" alt="">
                        </div>
                        <div class="position-absolute start-0 bottom-0">
                            <img src="assets/img/bg/line-02.svg" alt="">
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
            <!-- end row -->

            <div class="counter-list">

                <!-- start row -->
                <div class="row">

                    <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="1000">
                        <div class="counting-item flex-fill">
								<span class="count-icon">
									<img src="assets/img/icons/count-01.svg" alt="">
								</span>
                            <div>
                                <h4 class="mb-1"><span class="counter-up">12000</span>+</h4>
                                <p class="mb-0">Rentals Completed</p>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="1500">
                        <div class="counting-item flex-fill">
								<span class="count-icon">
									<img src="assets/img/icons/count-02.svg" alt="">
								</span>
                            <div>
                                <h4 class="mb-1"><span class="counter-up">1514</span>+</h4>
                                <p class="mb-0">Trusted Owners</p>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="2000">
                        <div class="counting-item flex-fill">
								<span class="count-icon">
									<img src="assets/img/icons/count-03.svg" alt="">
								</span>
                            <div>
                                <h4 class="mb-1"><span class="counter-up">9</span>K+</h4>
                                <p class="mb-0">Happy Clients</p>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="2500">
                        <div class="counting-item flex-fill">
								<span class="count-icon">
									<img src="assets/img/icons/count-02.svg" alt="">
								</span>
                            <div>
                                <h4 class="mb-1"><span class="counter-up">1514</span>+</h4>
                                <p class="mb-0">Total Bookings</p>
                            </div>
                        </div>
                    </div> <!-- end col -->

                </div>
                <!-- end row -->

            </div>
        </div>
    </section>
    <!-- About Us Section End -->

    <!-- Property Type Section Start -->
    <section class="property-type-section">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2" data-aos="fade-up" data-aos-duration="1000">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Recommended <span> Property</span> Types </h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Discover our top service areas, where quality meets convenience.</p>
            </div>
            <!-- Section Title End -->

            <!-- start row -->
            <div class="row">

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="property-type-item">
                        <div class="property-img">
                            <a href="buy-details.html"><img src="assets/img/property-type/property-type-01.jpg" class="img-fluid" alt=""></a>
                            <a href="buy-details.html" class="overlay-arrow"><i class="material-icons-outlined">north_east</i></a>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-1"><a href="buy-details.html">Houses</a></h5>
                            <p class="fs-14 mb-0">288 Property Available</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="1500">
                    <div class="property-type-item">
                        <div class="property-img">
                            <a href="buy-details.html"><img src="assets/img/property-type/property-type-02.jpg" class="img-fluid" alt=""></a>
                            <a href="buy-details.html" class="overlay-arrow"><i class="material-icons-outlined">north_east</i></a>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-1"><a href="buy-details.html">Offices</a></h5>
                            <p class="fs-14 mb-0">300 Property Available</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="2000">
                    <div class="property-type-item">
                        <div class="property-img">
                            <a href="buy-details.html"><img src="assets/img/property-type/property-type-03.jpg" class="img-fluid" alt=""></a>
                            <a href="buy-details.html" class="overlay-arrow"><i class="material-icons-outlined">north_east</i></a>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-1"><a href="buy-details.html">Villas</a></h5>
                            <p class="fs-14 mb-0">145 Property Available</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="2500">
                    <div class="property-type-item">
                        <div class="property-img">
                            <a href="buy-details.html"><img src="assets/img/property-type/property-type-04.jpg" class="img-fluid" alt=""></a>
                            <a href="buy-details.html" class="overlay-arrow"><i class="material-icons-outlined">north_east</i></a>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-1"><a href="buy-details.html">Apartments</a></h5>
                            <p class="fs-14 mb-0">875  Property Available</p>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
            <!-- end row -->

            <div class="text-center pt-3">
                <a href="buy-property-grid.html" class="btn btn-dark d-inline-flex align-items-center">View More Type<i class="material-icons-outlined ms-1">north_east</i></a>
            </div>

        </div>
    </section>
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
    <section class="popular-listing-section">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Discover <span> Popular</span> Listing</h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Ready to buy your dream home? find it here</p>
            </div>
            <!-- Section Title End -->

            <ul class="nav nav-pills listing-nav-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#listing-1" role="tab" aria-controls="listing-1" aria-selected="true">
                        For Rent
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#listing-2" role="tab" aria-controls="listing-2" aria-selected="false" tabindex="-1">
                        For Sale
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade active show" id="listing-1" role="tabpanel">

                    <!-- start row -->
                    <div class="row">

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="rent-details.html">
                                        <img class="img-fluid rounded" src="assets/img/buy/buy-grid-img-01.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="badge badge-sm bg-danger d-flex align-items-center">
                                                <i class="material-icons-outlined">offline_bolt</i>Trending
                                            </div>
                                            <div class="badge badge-sm bg-orange d-flex align-items-center">
                                                <i class="material-icons-outlined">loyalty</i>Featured
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Rent</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-01.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="rent-details.html">Serenity Condo Suite</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i> 17, Grove Towers, New York, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Condo</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <span class="ms-1 fs-14">5.0</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1596</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            2 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            1 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            400 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="rent-details.html">
                                        <img class="img-fluid rounded" src="assets/img/buy/buy-grid-img-02.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="badge badge-sm bg-danger d-flex align-items-center">
                                                <i class="material-icons-outlined">offline_bolt</i>Trending
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Rent</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-02.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="rent-details.html">Loyal Apartment</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>25, Willow Crest Apartment, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Apartment</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star_half</i>
                                            <span class="ms-1 fs-14">4.5</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1940</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            2 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            2 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            350 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="rent-details.html">
                                        <img class="img-fluid rounded" src="assets/img/buy/buy-grid-img-03.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-orange d-flex align-items-center">
                                            <i class="material-icons-outlined">loyalty</i>Featured
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Rent</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-03.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="rent-details.html">Grand Villa House</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>10, Oak Ridge Villa, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Villa</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <span class="ms-1 fs-14">4.9</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1370</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            4 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            3 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            520 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="rent-details.html">
                                        <img class="img-fluid rounded" src="assets/img/buy/buy-grid-img-04.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-orange d-flex align-items-center">
                                            <i class="material-icons-outlined">loyalty</i>Featured
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Rent</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-04.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="rent-details.html">Palm Cove Bungalows</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>42, Pine Residency, Miami, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Bungalow</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star_half</i>
                                            <span class="ms-1 fs-14">4.5</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1560</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            5 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            3 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            700 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="rent-details.html">
                                        <img class="img-fluid rounded" src="assets/img/buy/buy-grid-img-05.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-danger d-flex align-items-center">
                                            <i class="material-icons-outlined">offline_bolt</i>Trending
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Rent</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-05.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="rent-details.html">Blue Horizon Villa</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>76, Golden Oaks, Dallas, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Villa</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <span class="ms-1 fs-14">5.0</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$2000</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            2 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            1 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            400 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="rent-details.html">
                                        <img class="img-fluid rounded" src="assets/img/buy/buy-grid-img-06.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-danger d-flex align-items-center">
                                            <i class="material-icons-outlined">offline_bolt</i>Trending
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Rent</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-06.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="rent-details.html">Wanderlust Lodge</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>91, Birch Residences, Boston, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Lodge</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star_border</i>
                                            <span class="ms-1 fs-14">4.0</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1950</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            3 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            2 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            550 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-md-12">
                            <div class="text-center pt-3">
                                <a href="rent-property-grid.html" class="btn btn-dark d-inline-flex align-items-center">Explore all Listings<i class="material-icons-outlined ms-1">north_east</i></a>
                            </div>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->

                </div>

                <div class="tab-pane fade" id="listing-2" role="tabpanel">

                    <!-- start row -->
                    <div class="row">

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="buy-details.html">
                                        <img class="img-fluid rounded" src="assets/img/rent/rent-grid-img-01.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="badge badge-sm bg-danger d-flex align-items-center">
                                                <i class="material-icons-outlined">offline_bolt</i>Trending
                                            </div>
                                            <div class="badge badge-sm bg-orange d-flex align-items-center">
                                                <i class="material-icons-outlined">loyalty</i>Featured
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Sale</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-07.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="buy-details.html">Stylish Skyline Room</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i> 17, Grove Towers, New York, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Condo</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <span class="ms-1 fs-14">5.0</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1596</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            2 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            1 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            400 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="buy-details.html">
                                        <img class="img-fluid rounded" src="assets/img/rent/rent-grid-img-02.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="badge badge-sm bg-danger d-flex align-items-center">
                                                <i class="material-icons-outlined">offline_bolt</i>Trending
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Sale</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-08.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="buy-details.html">Getaway Apartment</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>25, Willow Crest Apartment, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Apartment</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star_half</i>
                                            <span class="ms-1 fs-14">4.5</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1940</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            2 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            2 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            350 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="buy-details.html">
                                        <img class="img-fluid rounded" src="assets/img/rent/rent-grid-img-03.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-orange d-flex align-items-center">
                                            <i class="material-icons-outlined">loyalty</i>Featured
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Sale</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-09.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="buy-details.html">Cozy Urban Condo</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>10, Oak Ridge Villa, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Villa</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <span class="ms-1 fs-14">4.9</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1370</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            4 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            3 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            520 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="buy-details.html">
                                        <img class="img-fluid rounded" src="assets/img/rent/rent-grid-img-04.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-orange d-flex align-items-center">
                                            <i class="material-icons-outlined">loyalty</i>Featured
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Sale</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-10.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="buy-details.html">Coral Bay Cabins</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>42, Pine Residency, Miami, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Bungalow</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star_half</i>
                                            <span class="ms-1 fs-14">4.5</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1560</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            5 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            3 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            700 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="buy-details.html">
                                        <img class="img-fluid rounded" src="assets/img/rent/rent-grid-img-05.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-danger d-flex align-items-center">
                                            <i class="material-icons-outlined">offline_bolt</i>Trending
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Sale</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-11.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="buy-details.html">Majestic Stay</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>76, Golden Oaks, Dallas, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Villa</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <span class="ms-1 fs-14">5.0</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$2000</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            2 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            1 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            400 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            <div class="property-listing-item">
                                <div class="buy-grid-img">
                                    <a href="buy-details.html">
                                        <img class="img-fluid rounded" src="assets/img/rent/rent-grid-img-06.jpg" alt="">
                                    </a>
                                    <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
                                        <div class="badge badge-sm bg-danger d-flex align-items-center">
                                            <i class="material-icons-outlined">offline_bolt</i>Trending
                                        </div>
                                        <a href="javascript:void(0)" class="favourite">
                                            <i class="material-icons-outlined">favorite_border</i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
                                        <span class="badge bg-light text-dark">For Sale</span>
                                        <div class="user-avatar avatar avatar-md">
                                            <img src="assets/img/users/user-12.jpg" alt="User" class="rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="buy-grid-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="title">
                                                <a href="buy-details.html">Noble Nest</a>
                                            </h6>
                                            <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i>91, Birch Residences, Boston, USA</p>
                                        </div>
                                        <span class="badge bg-secondary">Lodge</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star</i>
                                            <i class="material-icons-outlined text-warning">star_border</i>
                                            <span class="ms-1 fs-14">4.0</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span>Starts From</span>
                                            <h6 class="text-primary mb-0 ms-1">$1950</h6>
                                        </div>
                                    </div>
                                    <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bed</i>
                                            3 Bedroom
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">bathtub</i>
                                            2 Bath
                                        </li>
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="material-icons-outlined bg-light text-dark">straighten</i>
                                            550 Sq Ft
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-md-12">
                            <div class="text-center pt-3">
                                <a href="buy-property-grid.html" class="btn btn-dark d-inline-flex align-items-center">Explore all Listings<i class="material-icons-outlined ms-1">north_east</i></a>
                            </div>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->

                </div>

            </div>
        </div>
    </section>
    <!-- Popular Listing Section End -->

    <!-- Exclusive Benifits Section Start -->
    <section class="exclusive-benifit-section">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Discover the <span> Advantages & Exclusive</span> Benefits</h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Along the way, we’ve collaborated with incredible clients, tackled exciting challenges</p>
            </div>
            <!-- Section Title End -->

            <!-- start row -->
            <div class="row">

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="benifit-item">
							<span class="benifit-icon">
								<i class="material-icons-outlined">check_circle</i>
							</span>
                        <div>
                            <h5 class="mb-2">Verified Listings</h5>
                            <p class="mb-0">All properties are thoroughly checked to ensure authenticity and avoid time-wasting.</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                    <div class="benifit-item">
							<span class="benifit-icon">
								<i class="material-icons-outlined">check_circle</i>
							</span>
                        <div>
                            <h5 class="mb-2">Wide Reach</h5>
                            <p class="mb-0">Access thousands of listings across top cities, towns, and emerging locations.</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="2000">
                    <div class="benifit-item">
							<span class="benifit-icon">
								<i class="material-icons-outlined">check_circle</i>
							</span>
                        <div>
                            <h5 class="mb-2">Direct Communication</h5>
                            <p class="mb-0">Connect instantly with sellers, agents, or property managers—no middlemen.</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="2500">
                    <div class="benifit-item">
							<span class="benifit-icon">
								<i class="material-icons-outlined">check_circle</i>
							</span>
                        <div>
                            <h5 class="mb-2">Expert Guidance</h5>
                            <p class="mb-0">Receive professional insights to make informed real estate decisions confidently.</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="3000">
                    <div class="benifit-item">
							<span class="benifit-icon">
								<i class="material-icons-outlined">check_circle</i>
							</span>
                        <div>
                            <h5 class="mb-2">Tailored Solutions</h5>
                            <p class="mb-0">We customize property options based on your specific needs and preferences.</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="3000">
                    <div class="benifit-item">
							<span class="benifit-icon">
								<i class="material-icons-outlined">check_circle</i>
							</span>
                        <div>
                            <h5 class="mb-2">Seamless Process</h5>
                            <p class="mb-0">Enjoy a smooth, stress-free experience from property search to final transaction.</p>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
            <!-- end row -->

            <div class="sec-bottom-imgs">
                <div class="bottom-img-1"><img src="assets/img/benifits/benifit-01.jpg" alt=""></div>
                <div class="bottom-img-2"><img src="assets/img/benifits/benifit-02.jpg" alt=""></div>
                <div class="bottom-img-3"><img src="assets/img/benifits/benifit-03.jpg" alt=""></div>
                <div class="bottom-img-4"><img src="assets/img/benifits/benifit-04.jpg" alt=""></div>
                <div class="bottom-img-5"><img src="assets/img/benifits/benifit-05.jpg" alt=""></div>
                <div class="bottom-img-6"><img src="assets/img/benifits/benifit-06.jpg" alt=""></div>
            </div>

        </div>
    </section>
    <!-- Exclusive Benifits Section End -->

    <!-- Feature Location Section Start -->
    <section class="feature-location-section">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Discover <span> Featured</span> Location</h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Ready to buy your dream home? find it here</p>
            </div>
            <!-- Section Title End -->

            <!-- end row -->
            <div class="row">

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="location-item-two">
                        <div class="location-img">
                            <a href="javascript:void(0)"><img src="assets/img/location/location-01.jpg" class="img-fluid" alt=""></a>
                            <div class="position-absolute top-0 end-0 p-3 z-1"><span class="badge bg-light text-dark">200 Properties</span></div>
                            <h5 class="position-absolute start-0 bottom-0 text-white z-1 p-3">Switzerland</h5>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="1500">
                    <div class="location-item-two">
                        <div class="location-img">
                            <a href="javascript:void(0)"><img src="assets/img/location/location-02.jpg" class="img-fluid" alt=""></a>
                            <div class="position-absolute top-0 end-0 p-3 z-1"><span class="badge bg-light text-dark">156 Properties</span></div>
                            <h5 class="position-absolute start-0 bottom-0 text-white z-1 p-3">Argentina</h5>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="2000">
                    <div class="location-item-two">
                        <div class="location-img">
                            <a href="javascript:void(0)"><img src="assets/img/location/location-03.jpg" class="img-fluid" alt=""></a>
                            <div class="position-absolute top-0 end-0 p-3 z-1"><span class="badge bg-light text-dark">238 Properties</span></div>
                            <h5 class="position-absolute start-0 bottom-0 text-white z-1 p-3">Singapore</h5>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="2500">
                    <div class="location-item-two">
                        <div class="location-img">
                            <a href="javascript:void(0)"><img src="assets/img/location/location-04.jpg" class="img-fluid" alt=""></a>
                            <div class="position-absolute top-0 end-0 p-3 z-1"><span class="badge bg-light text-dark">145 Properties</span></div>
                            <h5 class="position-absolute start-0 bottom-0 text-white z-1 p-3">Thailand</h5>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
            <!-- end row -->

            <div class="text-center pt-3">
                <a href="javascript:void(0)" class="btn btn-dark d-inline-flex align-items-center">More Locations<i class="material-icons-outlined ms-1">north_east</i></a>
            </div>

        </div>
    </section>
    <!-- Feature Location Section End -->

    <!-- Work Section Start -->
    <section class="work-section">
        <div class="container">

            <!-- start row -->
            <div class="row align-items-center justify-content-lg-end"	>

                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
                        <div class="d-flex align-items-center mb-3">
                            <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                            <span class="text-white d-inline-block ms-2">Post Your Property</span>
                        </div>
                        <h2>Looking to Post your Exsisting Property We Provide Awesome Solution</h2>
                        <p>Whether you're selling or renting, we make it easy to post your property and reach the right audience with powerful tools and expert support.</p>
                        <a href="add-property-buy.html" class="btn btn-primary">Start Post Your Property</a>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-6">
                    <div class="card work-item border-0 mb-0">
                        <div class="card-body">
                            <div class="mb-4">
                                <span class="badge bg-secondary mb-2">How it Works</span>
                                <h2>Want tailor this more for a specific niche</h2>
                            </div>
                            <div class="work-steps">
                                <h6 class="fw-semibold fs-16 mb-1 text-secondary">01. Search for Location</h6>
                                <p class="mb-0 fs-14">Search by location, category, budget, and amenities. Find listings that match your needs—whether it's a home, office, or land.</p>
                            </div>
                            <div class="work-steps">
                                <h6 class="fw-semibold fs-16 mb-1 text-teal">02. Select Property Type</h6>
                                <p class="mb-0 fs-14">Choose from modern apartments, spacious houses, stylish condos, or commercial spaces that meet your specific needs.</p>
                            </div>
                            <div class="work-steps mb-0">
                                <h6 class="fw-semibold fs-16 mb-1 text-purple">03. Book Your Property</h6>
                                <p class="mb-0 fs-14">Select your preferred property type, provide your details, and confirm your booking in just a few easy steps.</p>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->

            </div>
            <!-- end row -->

        </div>
    </section>
    <!-- Work Section End -->

    <!-- Customer Review Section Start -->
    <section class="customer-review-section">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
                <div class="d-flex align-items-center justify-content-center">
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                    <h2>Success <span> Stories</span> in their Own Words</h2>
                    <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                </div>
                <p>Hear from our happy customers</p>
            </div>
            <!-- Section Title End -->

            <div class="review-slider swiper" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <div class="review-item-two swiper-slide">
                        <span class="mb-3 d-block"><img src="assets/img/icons/quote-down.svg" class="w-auto m-auto" alt=""></span>
                        <div class="review-content">
                            <p class="mb-2">Booking our dream home was incredibly easy with Dreams Estate The interface was user-friendly</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2"><img src="assets/img/users/user-02.jpg" alt=""></a>
                            <div>
                                <h6 class="fs-16 fw-semibold mb-0"><a href="javascript:void(0)">Lily Brooks</a></h6>
                                <span class="fs-14">South Africa</span>
                            </div>
                        </div>
                    </div>
                    <div class="review-item-two swiper-slide">
                        <span class="mb-3 d-block"><img src="assets/img/icons/quote-down.svg" class="w-auto m-auto" alt=""></span>
                        <div class="review-content">
                            <p class="mb-2">Dreams Estate made home booking a breeze. Super easy and stress-free! listing Portal of all time</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2"><img src="assets/img/users/user-04.jpg" alt=""></a>
                            <div>
                                <h6 class="fs-16 fw-semibold mb-0"><a href="javascript:void(0)">Daniel Cooper</a></h6>
                                <span class="fs-14">United States of America</span>
                            </div>
                        </div>
                    </div>
                    <div class="review-item-two swiper-slide">
                        <span class="mb-3 d-block"><img src="assets/img/icons/quote-down.svg" class="w-auto m-auto" alt=""></span>
                        <div class="review-content">
                            <p class="mb-2">Dreams Estate made home booking a breeze. Super easy and stress-free! listing Portal of all time</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2"><img src="assets/img/users/user-03.jpg" alt=""></a>
                            <div>
                                <h6 class="fs-16 fw-semibold mb-0"><a href="javascript:void(0)">Amina</a></h6>
                                <span class="fs-14">North German Union</span>
                            </div>
                        </div>
                    </div>
                    <div class="review-item-two swiper-slide">
                        <span class="mb-3 d-block"><img src="assets/img/icons/quote-down.svg" class="w-auto m-auto" alt=""></span>
                        <div class="review-content">
                            <p class="mb-2">Dreams Estate made home booking a breeze. Super easy and stress-free! listing Portal of all time</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2"><img src="assets/img/users/user-06.jpg" alt=""></a>
                            <div>
                                <h6 class="fs-16 fw-semibold mb-0"><a href="javascript:void(0)">Mohammed</a></h6>
                                <span class="fs-14">United Kingdom</span>
                            </div>
                        </div>
                    </div>
                    <div class="review-item-two swiper-slide">
                        <span class="mb-3 d-block"><img src="assets/img/icons/quote-down.svg" class="w-auto m-auto" alt=""></span>
                        <div class="review-content">
                            <p class="mb-2">Dreams Estate made home booking a breeze. Super easy and stress-free! listing Portal of all time</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                                <i class="material-icons-outlined text-warning">star</i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2"><img src="assets/img/users/user-04.jpg" alt=""></a>
                            <div>
                                <h6 class="fs-16 fw-semibold mb-0"><a href="javascript:void(0)">Daniel Cooper</a></h6>
                                <span>United States of America</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="text-center">
                <div class="d-inline-flex align-items-center flex-wrap gap-2 review-users">
                    <div class="avatar-list-stacked">
                        <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-01.jpg" class="img-fluid rounded-circle" alt="Img"></span>
                        <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-02.jpg" class="img-fluid rounded-circle" alt="Img"></span>
                        <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-03.jpg" class="img-fluid rounded-circle" alt="Img"></span>
                        <span class="avatar avatar-md rounded-circle border-0"><img src="assets/img/users/user-04.jpg" class="img-fluid rounded-circle" alt="Img"></span>
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
            </div>

        </div>
    </section>
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
