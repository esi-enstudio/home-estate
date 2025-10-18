@extends('layouts.app')

@section('title', 'Identity Verification | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">Identity Verification</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">পরিচয় যাচাইকরণ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <!-- Start Content -->
        <div class="content">
            <div class="container">
                <!-- start row -->
                <div class="row">
                    {{-- We are using a wider column for the form --}}
                    <div class="col-lg-8 mx-auto">
                        <div class="cart-item-wrap">
                            <div class="row">
                                <div class="col-lg-12">
                                    {{-- Here we are loading the Livewire component --}}
                                    @livewire('user-verification-form')
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- End Content -->

    </div>
@endsection
