@extends('layouts.app')

@section('title', (isset($property) ? 'Edit Property' : 'Add New Property') . ' | '. config('app.name'))

@section('content')
    <div class="page-wrapper">
        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">নতুন প্রোপার্টি তৈরি</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">প্রোপার্টি তৈরি</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ isset($property) ? 'প্রোপার্টির তথ্য এডিট করুন' : 'আপনার প্রোপার্টির তথ্য দিন' }}</h4>
                            </div>
                            <div class="card-body">
                                {{-- এখানে আমাদের মূল Livewire কম্পোনেন্ট লোড হবে --}}
                                @livewire('property.create-form', ['property' => $property ?? null])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
