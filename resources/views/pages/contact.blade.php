@extends('layouts.app')

@section('title', $settings->breadcrumb_title ?? 'যোগাযোগ' .' | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">{{ $settings->breadcrumb_title ?? 'যোগাযোগ করুন' }}</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $settings->breadcrumb_subtitle ?? 'যোগাযোগ' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="contact-us-wrap-01">
            <div class="container">
                <div class="row align-items-center row-gap-3">
                    <div class="col-lg-6">
                        <div class="card border-0">
                            <div class="card-body p-4">
                                <h4 class="mb-2">{{ $settings->sales_title ?? 'আমাদের বিক্রয় দলের সাথে কথা বলুন' }}</h4>
                                <p class="mb-3">{{ $settings->sales_description ?? 'আপনার প্রয়োজন অনুযায়ী...' }}</p>
                                <p class="fw-semibold mb-0">টোল ফ্রি : {{ $settings->sales_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="card border-0 mb-0">
                            <div class="card-body p-4">
                                <h4 class="mb-2">{{ $settings->support_title ?? 'প্রোডাক্ট ও অ্যাকাউন্ট সাপোর্ট' }}</h4>
                                <p class="mb-3">{{ $settings->support_description ?? 'আপনার অ্যাকাউন্ট, ফিচার...' }}</p>
                                <a href="{{ route('faq') }}" class="btn btn-dark">সচরাচর জিজ্ঞাসিত প্রশ্ন</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ms-0 ms-lg-4">
                            <img src="{{ $settings->image_one ? Storage::url($settings->image_one) : asset('assets/img/contact-us/contact-us-img-01.jpg') }}" alt="যোগাযোগ" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-us-wrap-02">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="row align-items-center justify-content-center mb-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="contact-us-item-01">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons-outlined">mail</span>
                                        <div>
                                            <h6 class="mb-2">ইমেইল অ্যাড্রেস</h6>
                                            @foreach($settings->contact_emails ?? [] as $email)
                                                <p class="mb-0">{{ $email['address'] }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="contact-us-item-01">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons-outlined">call</span>
                                        <div>
                                            <h6 class="mb-2">ফোন নম্বর</h6>
                                            @foreach($settings->contact_phones ?? [] as $phone)
                                                <p class="mb-0">{{ $phone['number'] }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="contact-us-item-01">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons-outlined">location_on</span>
                                        <div>
                                            <h6 class="mb-2">ঠিকানা</h6>
                                            <p class="mb-0">{{ $settings->contact_address ?? 'ধানমন্ডি, ঢাকা, বাংলাদেশ' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center row-gap-3">
                        <div class="col-lg-6">
                            <img src="{{ $settings->image_two ? Storage::url($settings->image_two) : asset('assets/img/contact-us/contact-us-img-02.jpg') }}" alt="যোগাযোগ" class="img-fluid">
                        </div>
                        <div class="col-lg-6">
                            @livewire('contact-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="google-map pb-5">
            <iframe src="{{ $settings->map_iframe_url ?? 'https://www.google.com/maps/d/u/0/embed?mid=1-rYp-k4i4xsZmx3EINCcnu1U3hyIqHI&ehbc=2E312F' }}" width="640" height="480"></iframe>
        </div>
    </div>
@endsection
