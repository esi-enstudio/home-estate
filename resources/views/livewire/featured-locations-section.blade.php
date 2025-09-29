<section class="feature-location-section">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>আপনার পছন্দের <span>এলাকায়</span> খুঁজুন</h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>শহরের সেরা সব প্রাইম লোকেশনে আপনার স্বপ্নের বাড়ি এখন হাতের মুঠোয়।</p>
        </div>
        <!-- Section Title End -->

        @if($locations->isNotEmpty())
            <!-- start row -->
            <div class="row">
                @foreach($locations as $location)
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="{{ 1000 + ($loop->index * 500) }}">
                        <div class="location-item-two">
                            <div class="location-img">
                                <a href="#">
                                    <img src="{{ $location->getFirstMediaUrl('featured_location_image') ?: 'https://placehold.co/400x300' }}" class="img-fluid" alt="{{ $location->name_bn }}">
                                </a>
                                <div class="position-absolute top-0 end-0 p-3 z-1">
                                    <span class="badge bg-light text-dark">{{ $location->properties_count }} টি প্রপার্টি</span>
                                </div>
                                <h5 class="position-absolute start-0 bottom-0 text-white z-1 p-3">{{ $location->name_bn }}</h5>
                            </div>
                        </div>
                    </div> <!-- end col -->
                @endforeach
            </div>
            <!-- end row -->

            <div class="text-center pt-3">
                <a href="#" class="btn btn-dark d-inline-flex align-items-center">
                    সব লোকেশন দেখুন<i class="material-icons-outlined ms-1">north_east</i>
                </a>
            </div>
        @endif

    </div>
</section>
