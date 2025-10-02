@extends('layouts.app')

@section('title', 'About Us | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">আমাদের সম্পর্কে</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">আমাদের সম্পর্কে</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="about-us-item-06">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="about-us-item-01">
                            <h2>আমরা দালান এবং মানুষের মধ্যে সেতুবন্ধন তৈরি করি</h2>
                            <p class="mb-0">আমরা শুধু প্রপার্টি লেনদেনে বিশ্বাস করি না—আমরা অর্থবহ সংযোগ তৈরিতে বিশ্বাসী। আমাদের লক্ষ্য হলো প্রতিটি স্থাপনার সাথে তাদের প্রাণবন্ত অধিবাসীদের মেলবন্ধন ঘটানো। আপনি স্বপ্নের বাড়ি খুঁজছেন, অফিসের জন্য জায়গা বা রিয়েল এস্টেটে বিনিয়োগ করতে চান, আমাদের প্ল্যাটফর্ম আপনার জন্য সঠিক ঠিকানা খুঁজে পাওয়া সহজ করে দেয়। বিশ্বস্ত লিস্টিং, বিশেষজ্ঞের সহায়তা এবং আধুনিক প্রযুক্তির মাধ্যমে আমরা স্থাপনাকে গল্পে এবং দালানকে আপন ঠিকানায় রূপান্তরিত করি।</p>
                        </div>

                        <!-- start row -->
                        <div class="row row-gap-4 about-us-img-wrap">
                            <div class="col-md-4 col-lg-4">
                                <img src="{{ asset('assets/img/about-us/about-us-01.jpg') }}" alt="img" class="img-fluid rounded">
                            </div><!-- end col -->
                            <div class="col-md-4 col-lg-4">
                                <img src="{{ asset('assets/img/about-us/about-us-02.jpg') }}" alt="img" class="img-fluid rounded">
                            </div><!-- end col -->
                            <div class="col-md-4 col-lg-4">
                                <img src="{{ asset('assets/img/about-us/about-us-03.jpg') }}" alt="img" class="img-fluid rounded">
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->

                        {{-- Livewire কম্পোনেন্ট এখানে রেন্ডার হবে --}}
                        @livewire('about-us-stats')

                    </div><!-- end col -->
                </div>
            </div>
        </div>

        <div class="about-us-item-03">
            <img src="{{ asset('assets/img/bg/about-us-bg-01.png') }}" alt="" class="img-fluid about-us-bg-01 d-none d-lg-flex">
            <img src="{{ asset('assets/img/bg/about-us-bg-02.png') }}" alt="" class="img-fluid about-us-bg-02 d-none d-lg-flex">
            <div class="container">

                <!-- start row -->
                <div class="row align-items-center row-gap-4 position-relative z-2">
                    <div class="col-xl-5">
                        <div class="me-3">
                            <h2 class="mb-4">আপনার স্বপ্নের ঠিকানা বুক করতে প্রস্তুত?</h2>
                            <img src="{{ asset('assets/img/about-us/about-us-04.jpg') }}" alt="" class="img-fluid rounded w-100">
                        </div>
                    </div><!-- end col -->
                    <div class="col-xl-7">
                        <h5 class="mb-4">আমাদের সহজ, দ্রুত এবং ঝামেলাবিহীন বুকিং প্রক্রিয়ার মাধ্যমে অনায়াসে আপনার আদর্শ স্থানটি খুঁজে নিন এবং বুকিং নিশ্চিত করুন।</h5>
                        <p>আপনার জীবনযাত্রা এবং বাজেটের সাথে মানানসই যাচাইকৃত প্রপার্টি লিস্টিং-এর বিশাল সম্ভার থেকে ঘুরে আসুন। শহরের বিলাসবহুল অ্যাপার্টমেন্ট হোক বা শহরতলির শান্ত পরিবেশে একটি уютী বাড়ি, আমাদের প্ল্যাটফর্ম আপনাকে দেবে একটি মসৃণ এবং নির্ভরযোগ্য বুকিং অভিজ্ঞতা। নিরাপদ লেনদেন এবং তাৎক্ষণিক বুকিং কনফার্মেশনের সুবিধা উপভোগ করুন।</p>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

            </div>
        </div>


        <div class="about-us-item-04">
            <div class="container">
                <div class="row">
                    <div class="col-lg-11 mx-auto">
                        <div class="text-center about-us-item-05">
                            <h2 class="mb-3">বিশ্বজুড়ে শত শত অংশীদার</h2>
                            <p class="mb-0">প্রতিদিন আমরা যোগাযোগ, স্বচ্ছতা এবং ফলাফলের মাধ্যমে আস্থা তৈরি করি।</p>
                        </div>
                        {{-- Partner logos can be made dynamic with another component if needed --}}
                        <div class="row align-items-center row-gap-4">
                            {{-- ... Partner logo divs ... --}}
                        </div>
                    </div><!-- end col -->
                </div>
            </div>
        </div>
    </div>
@endsection
