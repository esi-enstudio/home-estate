@extends('layouts.app')

@section('title', 'All Property Types | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">সকল প্রোপার্টির ধরন</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">প্রোপার্টির ধরন</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <!-- Start Content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        @if($propertyTypes->isNotEmpty())
                            <div class="row">
                                @foreach($propertyTypes as $type)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="property-type-item">
                                            <div class="property-img">
                                                {{-- এই লিঙ্কে ক্লিক করলে ওই ক্যাটাগরির সকল প্রপার্টি দেখাবে --}}
                                                {{-- দ্রষ্টব্য: 'properties.index' নামে একটি রাউট আপনার থাকতে হবে --}}
                                                <a href="{{ route('properties.index', ['type' => $type->slug]) }}">
                                                    {{-- Spatie Media Library থেকে 'image' কালেকশনের ছবি দেখানো হচ্ছে --}}
                                                    {{-- ছবি না থাকলে একটি প্লেসহোল্ডার দেখানো হবে --}}
                                                    <img src="{{ $type->getFirstMediaUrl('image') ?: 'https://placehold.co/400x280' }}" class="img-fluid" alt="{{ $type->name_bn }}">
                                                </a>
                                                <a href="{{ route('properties.index', ['type' => $type->slug]) }}" class="overlay-arrow">
                                                    <i class="material-icons-outlined">north_east</i>
                                                </a>
                                            </div>
                                            <div class="text-center mt-3">
                                                <h5 class="mb-1"><a href="{{ route('properties.index', ['type' => $type->slug]) }}">{{ $type->name_bn }}</a></h5>
                                                <p class="fs-14 mb-0 text-muted">{{ $type->properties_count }} টি প্রপার্টি রয়েছে</p>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p>দুঃখিত, এই মুহূর্তে কোনো প্রোপার্টির ধরন খুঁজে পাওয়া যায়নি।</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->

    </div>
@endsection
