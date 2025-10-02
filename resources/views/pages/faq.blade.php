@extends('layouts.app')

@section('title', 'FAQ | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">FAQ</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">সচরাচর জিজ্ঞাসিত প্রশ্ন</li>
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
                <div class="row" id="cart-wrap">
                    <div class="col-lg-12 mx-auto">
                        <div class="cart-item-wrap">
                            <div class="row row-gap-3">
                                <div class="col-lg-12">
                                    @if($faqs->isNotEmpty())
                                        <div class="accordion accordion-bordered accordion-custom-icon accordion-arrow-none" id="faqAccordion">
                                            @foreach($faqs as $faq)
                                                <div class="accordion-item">
                                                    <h6 class="accordion-header" id="heading{{ $faq->id }}">
                                                        <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $faq->id }}">
                                                            {{ $faq->question_bn ?? $faq->question }}
                                                            <i class="ti ti-plus accordion-icon accordion-icon-on"></i>
                                                            <i class="ti ti-minus accordion-icon accordion-icon-off"></i>
                                                        </button>
                                                    </h6>
                                                    <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            {!! $faq->answer_bn ?? $faq->answer !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <p>দুঃখিত, এই মুহূর্তে কোনো প্রশ্ন ও উত্তর যোগ করা হয়নি।</p>
                                        </div>
                                    @endif
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
