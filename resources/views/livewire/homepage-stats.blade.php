<section class="about-us-section-2">
    <div class="container">
        <!-- start row -->
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <!-- Section Title Start -->
                <div class="title-head" data-aos="fade-up" data-aos-duration="500">
                    <span class="badge bg-secondary mb-2">আমাদের সম্পর্কে</span>
                    <h2 class="mb-2">আপনার স্বপ্নের বাড়ি খোঁজার পথকে আমরা করি সহজ, স্বচ্ছ এবং নিশ্চিন্ত।</h2>
                    <p>আমাদের সেবা আপনার হাতের নাগালে। উন্নতমানের প্রপার্টি, দ্রুততম সাড়া এবং গ্রাহক সন্তুষ্টির নিশ্চয়তা দিয়ে আমরা তৈরি করেছি এক নির্ভরযোগ্য প্ল্যাটফর্ম। আপনার স্বপ্নের ঠিকানা খুঁজে পেতে আজই শুরু করুন।</p>
                    <div class="d-flex align-items-center">
                        <a href="#" class="btn btn-dark btn-lg me-3">বিনামূল্যে প্রপার্টি বিজ্ঞাপন দিন</a>
                        <a href="#" class="btn btn-primary btn-lg">যোগাযোগ করুন</a>
                    </div>
                </div>
                <!-- Section Title End -->
            </div> <!-- end col -->

            <div class="col-lg-6">
                <div class="position-relative" data-aos="fade-up" data-aos-duration="1000">
                    <div><img src="{{ asset('assets/img/section-bg/section-bg-01.png') }}" class="img-fluid" alt="Home Estate About Us"></div>
                    <div class="position-absolute end-0 top-0"><img src="{{ asset('assets/img/bg/line-01.svg') }}" alt=""></div>
                    <div class="position-absolute start-0 bottom-0"><img src="{{ asset('assets/img/bg/line-02.svg') }}" alt=""></div>
                </div>
            </div> <!-- end col -->
        </div>
        <!-- end row -->

        <div class="counter-list">
            <!-- start row -->
            <div class="row">
                <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="1000">
                    <div class="counting-item flex-fill">
                        <span class="count-icon"><img src="{{ asset('assets/img/icons/count-01.svg') }}" alt=""></span>
                        <div>
                            <h4 class="mb-1"><span class="counter-up">{{ $rentalsCompleted }}</span>+</h4>
                            <p class="mb-0">সম্পন্ন ভাড়া</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="1500">
                    <div class="counting-item flex-fill">
                        <span class="count-icon"><img src="{{ asset('assets/img/icons/count-02.svg') }}" alt=""></span>
                        <div>
                            <h4 class="mb-1"><span class="counter-up">{{ $trustedOwners }}</span>+</h4>
                            <p class="mb-0">বিশ্বস্ত মালিক</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="2000">
                    <div class="counting-item flex-fill">
                        <span class="count-icon"><img src="{{ asset('assets/img/icons/count-03.svg') }}" alt=""></span>
                        <div>
                            <h4 class="mb-1"><span class="counter-up">{{ $happyClients }}</span>+</h4>
                            <p class="mb-0">সন্তুষ্ট গ্রাহক</p>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3 col-sm-6 d-flex" data-aos="fade-up" data-aos-duration="2500">
                    <div class="counting-item flex-fill">
                        <span class="count-icon"><img src="{{ asset('assets/img/icons/count-02.svg') }}" alt=""></span> {{-- আইকন পরিবর্তন করতে পারেন --}}
                        <div>
                            <h4 class="mb-1"><span class="counter-up">{{ $activeProperties }}</span>+</h4>
                            <p class="mb-0">সক্রিয় প্রপার্টি</p>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
</section>
