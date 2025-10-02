@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <!-- Home Banner Section Start -->
    @include('partials._home-banner')

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
                    <h5>আজই ফ্রিতে আপনার প্রোপার্টির বিজ্ঞাপন দিন।</h5>
                </div>
                <div class="support-item">
                    <h5>আজই ফ্রিতে আপনার প্রোপার্টির বিজ্ঞাপন দিন।</h5>
                </div>
                <div class="support-item">
                    <h5>আজই ফ্রিতে আপনার প্রোপার্টির বিজ্ঞাপন দিন।</h5>
                </div>
                <div class="support-item">
                    <h5>আজই ফ্রিতে আপনার প্রোপার্টির বিজ্ঞাপন দিন।</h5>
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
    <livewire:faq-section/>
    <!-- FAQ Section End -->

    <!-- Blog Section Start -->
    <livewire:latest-blogs-section/>
    <!-- Blog Section Start -->
@endsection
