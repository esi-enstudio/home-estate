@extends('layouts.app')

@section('title', 'Wishlist | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">Wishlist</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">পছন্দের প্রোপার্টি</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <!-- Start Content -->
        <div class="content">
            <div class="container">
                {{-- Livewire কম্পোনেন্ট এখানে রেন্ডার হবে --}}
                <livewire:wishlist-items/>
            </div>
        </div>
        <!-- End Content -->
    </div>
@endsection
