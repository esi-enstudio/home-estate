@extends('layouts.app')

@section('title', 'আমাদের প্রপার্টি ম্যাপ | ' . config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">প্রপার্টি ম্যাপ</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ম্যাপ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <!-- Start Content -->
        <div class="content pt-0 pb-5"> {{-- p-0 ক্লাসটি ডিফল্ট প্যাডিং সরিয়ে দেবে --}}
            <div class="container-fluid p-0"> {{-- container-fluid এবং p-0 ম্যাপকে পুরো প্রস্থ দেবে --}}

                <!-- Google My Maps Embed -->
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/d/u/0/embed?mid=1-rYp-k4i4xsZmx3EINCcnu1U3hyIqHI&ehbc=2E312F"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </div>
        <!-- End Content -->

    </div>
@endsection

@push('styles')
    {{-- এই CSS কোডটি ম্যাপটিকে রেসপন্সিভ এবং পুরো স্ক্রিন জুড়ে দেখাতে সাহায্য করবে --}}
    <style>
        .map-container {
            position: relative;
            width: 100%;
            /* 100vh থেকে হেডারের উচ্চতা বাদ দেওয়া হচ্ছে (আনুমানিক) */
            height: calc(100vh - 150px);
            overflow: hidden;
        }
        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endpush
