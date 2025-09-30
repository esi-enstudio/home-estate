@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadscrumb -->
        <div class="breadcrumb-bar">
            {{-- Breadcrumb images can be static --}}
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">আমাদের ব্লগ</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><span><i class="mdi mdi-home-outline me-1"></i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ব্লগ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadscrumb -->

        {{-- Livewire কম্পোনেন্ট এখানে রেন্ডার হবে --}}
        @livewire('blog-index')

    </div>
@endsection
