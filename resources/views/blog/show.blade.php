@extends('layouts.app')

@section('title', $post->title. ' | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">Blog Details</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="mdi mdi-home-outline me-1"></i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 30) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <!-- Start Content -->
        <div class="content">
            <div class="container">
                <div class="row blog-details-cover">
                    <div class="col-lg-10 mx-auto">
                        <a href="{{ route('blog.index') }}" class="d-flex align-items-center mb-4"><i class="material-icons-outlined me-1">arrow_back</i>সকল ব্লগে ফিরে যান</a>
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="blog-details-item-01">
                                    <div class="blog-details-img-01">
                                        <img src="{{ $post->getFirstMediaUrl('featured_post_image') ?: 'https://placehold.co/800x400' }}" alt="{{ $post->title }}" class="img-fluid">
                                    </div>
                                    <div class="blog-details-content-01">
                                        <a href="{{ route('blog.category', $post->category) }}" class="badge badge-sm bg-secondary fw-semibold">{{ $post->category->name }}</a>
{{--                                         <span class="badge badge-sm bg-secondary fw-semibold">{{ $post->category->name }}</span>--}}
                                        <h5>{{ $post->title }}</h5>
                                        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 author-details">
                                            <div class="d-flex align-items-center me-3">
                                                <a href="#">
                                                    <img src="{{ $post->user->avatar_url ?? 'https://placehold.co/100' }}" alt="{{ $post->user->name }}" class="avatar avatar-sm rounded-circle me-2">
                                                </a>
                                                <a href="#">{{ $post->user->name }}</a>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="d-inline-flex align-items-center"><i class="material-icons-outlined me-1">event</i>{{ $post->published_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="blog-body-content">
                                    {!! $post->body !!} {{-- Rich Text Editor থেকে আসা HTML দেখানোর জন্য --}}
                                </div>

                                <div class="card border-0 border-start border-3 border-primary bg-light mb-4">
                                    <div class="card-body"><div class="row align-items-center row-gap-2">
                                            <div class="col-lg-2"><img src="{{ $post->user->avatar_url ?? 'https://placehold.co/100' }}" alt="{{ $post->user->name }}" class="img-fluid avatar avatar-xxxl rounded-circle"></div>
                                            <div class="col-lg-10">
                                                <p class="fw-medium mb-1 text-primary">লেখক</p>
                                                <h5>{{ $post->user->name }}</h5>
                                                <p class="mb-0">{{ $post->user->bio }}</p>
                                            </div>
                                        </div></div>
                                </div>

                                {{-- Livewire কম্পোনেন্ট এখানে রেন্ডার হবে --}}
                                @livewire('article-feedback', ['post' => $post])

                            </div>
                        </div>
                    </div>
                </div>

                @if($relatedPosts->isNotEmpty())
                    <div class="blog-details-item-02">
                        <h5>সম্পর্কিত পোস্ট</h5>
                        <div class="blog-carousel-wrapper">
                            <div class="blog-carousel">
                                @foreach($relatedPosts as $related)
                                    <div>
                                        <div class="blog-item-01">
                                            <div class="blog-img"><a href="{{ route('blog.show', $related) }}"><img src="{{ $related->getFirstMediaUrl('featured_post_image') ?: 'https://placehold.co/400x250' }}" alt="{{ $related->title }}" class="img-fluid"></a></div>
                                            <div class="blog-content">
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                                                    {{-- <span class="badge badge-sm bg-secondary fw-semibold">{{ $related->category->name }}</span> --}}
                                                    <div class="d-flex align-items-center flex-wrap gap-3 author-details">
                                                        <div class="d-flex align-items-center me-3">
                                                            <a href="#"><img src="{{ $related->user->avatar_url ?? 'https://placehold.co/100' }}" alt="{{ $related->user->name }}" class="avatar avatar-sm rounded-circle me-2"></a>
                                                            <a href="#">{{ $related->user->name }}</a>
                                                        </div>
                                                        <div class="d-flex align-items-center"><span class="d-inline-flex align-items-center"><i class="material-icons-outlined me-1">event</i>{{ $related->published_at->format('d M Y') }}</span></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1"><a href="{{ route('blog.show', $related) }}">{{ $related->title }}</a></h5>
                                                    <p class="mb-0">{{ Str::limit(strip_tags($related->body), 100) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
