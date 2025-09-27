@extends('layouts.app')

@section('title', 'Property Details | '. config('app.name'))

@section('content')

    <div class="page-wrapper"
         id="property-details-page"
         data-view-increment-url="{{ route('properties.increment-view', $property) }}"
    >
        <div class="buy-details-header-item">
            <!-- Start Breadscrumb -->
            <div class="breadcrumb-bar custom-breadcrumb-bar">
                <div class="container">
                    <div class="row align-items-center text-center position-relative z-1">
                        <div class="col-xl-8">
                            <div class="d-flex align-center gap-2 mb-2">
                                <span class="badge bg-primary">{{ $property->propertyType->name_en }}</span>
                                <span class="badge bg-secondary">For {{ ucfirst($property->purpose) }}</span>
                            </div>
                            <h1 class="breadcrumb-title text-start ">{{ $property->title }}</h1>
                            <div class="d-flex align-items-center gap-2 flex-wrap gap-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="material-icons-outlined text-warning">{{ $i <= $property->average_rating ? 'star' : 'star_border' }}</i>
                                    @endfor
                                    <span class="text-white ms-1"> {{ number_format($property->average_rating, 1) }} </span>
                                </div>
                                <i class="fa-solid fa-circle text-body"></i>
                                <div class="fs-14 mb-0 text-white d-flex align-items-center flex-wrap gap-1 custom-address-item">
                                    <i class="material-icons-outlined text-white me-1">location_on</i>{{ $property->address_street }}, {{ $property->address_area }}, {{ $property->district->name }}
                                    @if($property->google_maps_location_link)
                                        <a href="{{ $property->google_maps_location_link }}" target="_blank" class="text-primary fs-14 text-decoration-underline ms-1"> View Location</a>
                                    @endif
                                </div>
                                <i class="fa-solid fa-circle text-body"></i>
                                <p class="fs-14 mb-0 text-white">Last Updated on : {{ $property->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-xl-4 d-flex d-xl-block flex-wrap gap-3">
                            <div class="breadcrumb-icons d-flex align-items-center justify-content-xl-end justify-content-start gap-2 mb-xl-4 mb-2 mt-xl-0 mt-4">
                                <a href="javascript:void(0);" class=""><i class="material-icons-outlined rounded">favorite_border</i></a>
                                <a href="javascript:void(0);" class=""><i class="material-icons-outlined rounded">bookmark_add</i></a>
                                <a href="javascript:void(0);" class=""><i class="material-icons-outlined rounded">compare_arrows</i></a>
                            </div>
                            <div class="d-flex align-items-center gap-3 justify-content-xl-end justify-content-start">
                                <h4 class="mb-0 text-primary text-xl-end text-start"> ৳{{ number_format($property->rent_price) }} <span class="fs-14 fw-normal text-white">/ {{ ucfirst($property->rent_type) }}</span> </h4>
                                <a href="#" class="btn btn-primary btn-lg d-flex align-items-center"><i class="material-icons-outlined rounded me-1">calendar_today</i>Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Breadcrumb -->
        </div>

        <!-- Start Content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="mb-4 d-inline-flex align-center justify-content-between w-100 flex-wrap gap-1">
                            <div class="d-inline-flex align-center gap-2">
                                @if($property->is_trending)
                                    <span class="badge bg-danger d-flex align-items-center"> <i class="material-icons-outlined fs-14 me-1">generating_tokens</i> Trending </span>
                                @endif
                                @if($property->is_featured)
                                    <span class="badge bg-orange d-flex align-items-center"> <i class="material-icons-outlined fs-14 me-1">loyalty</i> Featured </span>
                                @endif
                            </div>
                            <p class="mb-0 text-dark">
                                Total No of Visits : {{ $property->views_count }}
                            </p>
                        </div>


                        @php
                            // একটি কালেকশনে প্রথমে ফিচার্ড ইমেজ এবং তারপর গ্যালারির ইমেজগুলো একত্রিত করা হচ্ছে
                            $featuredImage = $property->getFirstMedia('featured_image');
                            $galleryImages = $property->getMedia('gallery');

                            $allImages = collect(); // একটি খালি কালেকশন তৈরি করা হলো

                            if ($featuredImage) {
                                $allImages->push($featuredImage); // প্রথমে ফিচার্ড ইমেজ যোগ করা হলো
                            }

                            // গ্যালারির বাকি ছবিগুলো মার্জ করা হলো
                            $allImages = $allImages->merge($galleryImages);
                        @endphp

                        {{-- যদি কোনো ছবি আপলোড করা থাকে, তবেই কেবল স্লাইডারটি দেখানো হবে --}}
                        @if($allImages->isNotEmpty())
                            <!-- start slider -->
                            <div class="slider-card service-slider-card mb-4">
                                <div class="slide-part mb-4">
                                    <div class="slider service-slider">
                                        {{-- একত্রিত করা কালেকশনের উপর লুপ চালানো হচ্ছে --}}
                                        @foreach($allImages as $media)
                                            <div class="service-img-wrap">
                                                {{--
                                                    এই কোডটি স্বয়ংক্রিয়ভাবে একটি সম্পূর্ণ রেসপন্সিভ <img> ট্যাগ তৈরি করবে।
                                                    এটি srcset এবং sizes অ্যাট্রিবিউট ব্যবহার করে ব্রাউজারের স্ক্রিন সাইজ অনুযায়ী
                                                    সঠিক সাইজের ছবিটি লোড করবে।
                                                --}}
                                                {{ $media->img()->attributes(['class' => 'img-fluid', 'alt' => $property->title . ' - Image ' . $loop->iteration, 'loading' => 'lazy']) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="slider slider-nav-thumbnails text-center">
                                    @foreach($allImages as $media)
                                        <div class="slide-img">
                                            {{--
                                                এখানে আমরা বিশেষভাবে 'thumbnail' নামের WebP কনভার্সনটি ব্যবহার করছি।
                                                এটি ছোট, অপটিমাইজড এবং দ্রুত লোড হবে।
                                            --}}
                                            <img src="{{ $media->getUrl('thumbnail') }}" class="img-fluid" alt="{{ $property->title }} - Thumbnail {{ $loop->iteration }}" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- End slider -->

                        <div class="accordion accordions-items-seperate">
                            <!-- descritpion items -->
                            <div class="accordion-item">
                                <div class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-1">Description</button></div>
                                <div id="accordion-1" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <p>{!! ($property->description) !!}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Property items -->
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-2" aria-expanded="true">
                                        Property Features
                                    </button>
                                </div>
                                <div id="accordion-2" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="row row-gap-4">
                                            {{-- আমরা এখন মডেলের নতুন মেথড থেকে পাওয়া অ্যারের উপর লুপ চালাব --}}
                                            @forelse ($property->getFormattedFeatures() as $feature)
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="buy-property-items">
                                                        <p>
                                                            <i class="material-icons-outlined">{{ $feature['icon'] }}</i>
                                                            {{ $feature['label'] }}: {{ $feature['value'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @empty
                                                {{-- যদি getFormattedFeatures() কোনো ফলাফল না দেয় --}}
                                                <div class="col-12">
                                                    <p>No specific features have been listed for this property.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- House Rules & Guidelines -->
                            @if($property->house_rules)
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        {{-- SEO: h2, h3 ট্যাগ ব্যবহার করা ভালো --}}
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-3" aria-expanded="true">
                                                House Rules & Guidelines
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="accordion-3" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            @php
                                                // house_rules-এর টেক্সটকে প্রতিটি লাইন অনুযায়ী একটি অ্যারেতে বিভক্ত করা হচ্ছে
                                                // PREG_SPLIT_NO_EMPTY নিশ্চিত করে যে কোনো খালি লাইন অ্যারেতে আসবে না
                                                $rules = preg_split('/\\r\\n|\\r|\\n/', $property->house_rules, -1, PREG_SPLIT_NO_EMPTY);
                                            @endphp

                                            @foreach($rules as $rule)
                                                {{-- প্রতিটি নিয়মের জন্য ডিজাইনের ফরম্যাট অনুযায়ী HTML তৈরি হচ্ছে --}}
                                                <p class="mb-2">
                                                    <i class="fa-solid fa-circle-check text-success me-2 fs-18"></i>
                                                    {{-- SEO: htmlspecialchars ব্যবহার করে টেক্সটকে নিরাপদ রাখা হচ্ছে --}}
                                                    {{ htmlspecialchars($rule) }}
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- amenities items -->
                            @if($property->amenities->isNotEmpty())
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-4">
                                            Amenities
                                        </button>
                                    </div>
                                    <div id="accordion-4" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="row row-gap-4">
                                                @foreach($property->amenities as $amenity)
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="buy-property-items">
                                                            <p>
                                                                {{--
                                                                    এখানে আমরা নতুন Accessor ব্যবহার করছি।
                                                                    {!! !!} ব্যবহার করা হয়েছে কারণ আমাদের মেথডটি raw HTML রিটার্ন করছে।
                                                                --}}
                                                                {!! $amenity->icon_html !!}
                                                                {{ $amenity->name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php
                                // গ্যালারির জন্য আপলোড করা সকল মিডিয়া ফাইল একটি ভ্যারিয়েবলে নিয়ে আসা হলো
                                $galleryImages = $property->getMedia('gallery');
                            @endphp

                            {{-- যদি 'gallery' কালেকশনে একটির বেশি ছবি থাকে, তবেই এই সেকশনটি দেখানো হবে --}}
                            @if($galleryImages->isNotEmpty())
                                <!-- gallery items -->
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-6" aria-expanded="true">
                                            Gallery
                                        </button>
                                    </div>
                                    <div id="accordion-6" class="accordion-collapse collapse show">
                                        <div class="accordion-body gallery-body">
                                            <div class="gallery-slider">

                                                {{-- গ্যালারির প্রতিটি ছবির জন্য লুপ চালানো হচ্ছে --}}
                                                @foreach($galleryImages as $image)
                                                    <div class="gallery-card">
                                                        {{--
                                                            <a> ট্যাগের href এ মূল, বড় ছবিটি থাকবে যা FancyBox এ দেখাবে।
                                                            getUrl() মেথডটি মূল ছবিটি রিটার্ন করে।
                                                        --}}
                                                        <a href="{{ $image->getUrl() }}" data-fancybox="gallery" class="gallery-item rounded">
                                                            {{--
                                                                <img> ট্যাগের src এ আমরা 'preview' নামের WebP কনভার্সনটি ব্যবহার করছি।
                                                                এটি অপটিমাইজড এবং দ্রুত লোড হবে।
                                                            --}}
                                                            <img src="{{ $image->getUrl('preview') }}"
                                                                 alt="{{ $property->title }} - Gallery Image {{ $loop->iteration }}"
                                                                 class="rounded img-fluid"
                                                                 loading="lazy">
                                                        </a>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- video items -->
                            @if($property->video_url)
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-7">Video</button>
                                    </div>
                                    <div id="accordion-7" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="video-items position-relative">
                                                {{--
                                                    ফিচার্ড ইমেজ কালেকশন থেকে 'preview' নামের কনভার্সনটি ব্যবহার করা হচ্ছে।
                                                    যদি কোনো ছবি না থাকে, তাহলে placeholder দেখানো হবে।
                                                --}}
                                                <img src="{{ $property->getFirstMediaUrl('featured_image', 'preview') ?? asset('assets/img/default-video-bg.jpg') }}"
                                                     alt="Video Thumbnail for {{ $property->title }}"
                                                     class="img-fluid video-bg"
                                                >

                                                <a class="video-icon" data-fancybox="" href="{{ $property->video_url }}">
                                                    <i class="material-icons-outlined">play_circle_filled</i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- faq items -->
                            @if($property->faqs && count($property->faqs) > 0)
                                <div class="accordion-item">
                                    <div class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-8">Frequently Asked Questions</button></div>
                                    <div id="accordion-8" class="accordion-collapse collapse show">
                                        <div class="accordion-body"><div class="faq-items">
                                                @foreach($property->faqs as $faq)
                                                    <div class="faq-card">
                                                        <h4 class="faq-title"><a class="collapsed" data-bs-toggle="collapse" href="#faq{{ $loop->iteration }}">{{ $faq['question'] }}</a></h4>
                                                        <div id="faq{{ $loop->iteration }}" class="card-collapse collapse"><div class="faq-content"><p>{{ $faq['answer'] }}</p></div></div>
                                                    </div>
                                                @endforeach
                                            </div></div>
                                    </div>
                                </div>
                            @endif

                            <!-- reviews items -->
                            @livewire('property-reviews', ['property' => $property])

                        </div>
                    </div>

                    <div class="col-xl-4 theiaStickySidebar buy-details-item">
                        <!-- Provider Details -->
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">Provider Details</h5></div>
                            <div class="card-body">
                                <div class="card bg-light border-0 rounded shadow-none custom-btn mb-4">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-lg">
                                                <img src="{{ $property->user->profile_photo_path ?? 'https://via.placeholder.com/100' }}" alt="{{ $property->user->name }}" class="rounded-circle">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fs-16 fw-semibold"><a class="d-block w-100" href="#">{{ $property->user->name }}</a></h6>
                                                <p class="mb-0 fs-14 text-body">Property Owner</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border p-2 rounded mb-4">
                                    <a href="tel:{{ $property->user->phone }}" class="d-block mb-3 pb-3 border-bottom text-body d-flex align-items-center"><i class="material-icons-outlined text-body me-2 fs-16 p-1 bg-light rounded text-dark">phone</i> Call Us : {{ $property->user->phone }}</a>
                                    <a href="mailto:{{ $property->user->email }}" class="d-block text-body d-flex align-items-center"><i class="material-icons-outlined text-body me-2 fs-16 p-1 bg-light rounded text-dark">email</i>Email : {{ $property->user->email }}</a>
                                </div>
                            </div>
                        </div>

                        <!-- Enquiry Form -->
                        <div class="card">
                            {{-- This can be a Livewire component for handling form submission without page reload --}}
                            <div class="card-header"><h5 class="mb-0">Enquire Us</h5></div>
                            <div class="card-body">
                                <form>
                                    <div class="mb-3"><label class="form-label fw-semibold"> Name </label><input type="text" class="form-control" placeholder="Your Name"></div>
                                    <div class="mb-3"><label class="form-label fw-semibold"> Email </label><input type="email" class="form-control" placeholder="Your Email"></div>
                                    <div class="mb-3"><label class="form-label fw-semibold"> Phone </label><input type="text" class="form-control" placeholder="Your Phone Number"></div>
                                    <div class="mb-4"><label class="form-label fw-semibold"> Description </label><textarea class="form-control" rows="3" placeholder="I'm interested in this property..."></textarea></div>
                                    <div><button type="submit" class="btn btn-dark w-100 py-2 fs-14">Submit</button></div>
                                </form>
                            </div>
                        </div>

                        <!-- Map -->
                        @if($property->google_maps_location_link && (str_contains($property->google_maps_location_link, 'embed') || ($property->latitude && $property->longitude)))
                            <div class="card mb-0">
                                <div class="custom-map position-relative">
                                    {{-- Using latitude and longitude to build a standard embed link is more reliable --}}
                                    <iframe src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&hl=es;z=14&amp;output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Similar Properties -->
                @if($similarProperties->isNotEmpty())
                    <div class="row row-gap-4 custom-properties-items mt-5">
                        <div class="col-12">
                            <h3>Similar Properties</h3>
                        </div>
                        @foreach($similarProperties as $similar)
                            <div class="col-xl-3 col-lg-6 col-md-6 d-flex">
                                <div class="property-card mb-0 flex-fill">
                                    <div class="property-listing-item p-0 mb-0 shadow-none">
                                        <div class="buy-grid-img mb-0 rounded-0">
                                            <a href="{{ route('properties.show', $similar) }}">
                                                <img class="img-fluid" src="{{ $similar->getFirstMediaUrl('images', 'thumbnail') ?: 'https://via.placeholder.com/400x250' }}" alt="{{ $similar->title }}">
                                            </a>
                                            {{-- ... badges, favourite icon etc. ... --}}
                                        </div>
                                        <div class="buy-grid-content">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <span class="badge bg-secondary">{{ $similar->propertyType->name_en }}</span>
                                            </div>
                                            <div>
                                                <h6 class="title mb-1"><a href="{{ route('properties.show', $similar) }}">{{ Str::limit($similar->title, 25) }}</a></h6>
                                                <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1 ms-0">location_on</i>{{ $similar->address_area }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 border-top mt-3 pt-3">
                                                <h6 class="text-primary mb-0 ms-1">৳{{ number_format($similar->rent_price) }} <span class="fw-normal fs-14">/ {{ $similar->rent_type }}</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <!-- End Content -->
    </div>

@endsection
