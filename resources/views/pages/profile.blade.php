@extends('layouts.app')

@section('title', 'My Profile | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">আমার প্রোফাইল</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">প্রোফাইল</li>
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
                    <!-- Profile Sidebar -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                {{-- প্রোফাইল ছবি --}}
                                <img src="{{ $user->avatar_url ? Storage::url($user->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="User Avatar" class="img-fluid rounded-circle mb-3" width="120" height="120">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->phone ?? 'N/A' }}</p>
                                <p class="text-muted">{{ $user->email ?? 'N/A' }}</p>
                                <p class="text-muted">{{ $user->designation ?? 'N/A' }}</p>

                                {{-- রেটিং এবং রিভিউ --}}
                                @if($user->reviews_count > 0)
                                    <div class="d-flex justify-content-center align-items-center mb-3">
                                        <div class="rating me-2">
                                            <i class="fas fa-star @if($user->average_rating >= 1) text-warning @endif"></i>
                                            <i class="fas fa-star @if($user->average_rating >= 2) text-warning @endif"></i>
                                            <i class="fas fa-star @if($user->average_rating >= 3) text-warning @endif"></i>
                                            <i class="fas fa-star @if($user->average_rating >= 4) text-warning @endif"></i>
                                            <i class="fas fa-star @if($user->average_rating >= 5) text-warning @endif"></i>
                                        </div>
                                        <span class="fw-bold">{{ number_format($user->average_rating, 1) }}</span>
                                        <span class="text-muted ms-1">({{ $user->reviews_count }} রিভিউ)</span>
                                    </div>
                                @endif

                                <p class="text-sm">যোগদান: {{ $user->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- /Profile Sidebar -->

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab">প্রোফাইল তথ্য</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab">পাসওয়ার্ড পরিবর্তন</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification-tab-pane" type="button" role="tab">পরিচয় যাচাইকরণ</button>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="profileTabsContent">
                                    {{-- প্রোফাইল তথ্য ট্যাব --}}
                                    <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                                        {{-- তথ্য এডিট করার জন্য Livewire কম্পোনেন্ট এখানে লোড হবে --}}
                                        @livewire('profile.update-information', ['verificationStatus' => $verification?->status])
                                    </div>

                                    {{-- পাসওয়ার্ড পরিবর্তন ট্যাব --}}
                                    <div class="tab-pane fade" id="password-tab-pane" role="tabpanel">
                                        @livewire('profile.change-password')
                                    </div>

                                    {{-- পরিচয় যাচাইকরণ ট্যাব --}}
                                    <div class="tab-pane fade" id="verification-tab-pane" role="tabpanel" aria-labelledby="verification-tab" tabindex="0">
                                        <h5 class="card-title mb-4">আপনার পরিচয়পত্রের অবস্থা</h5>

                                        @if ($verification)
                                            <div class="alert
                                            @if($verification->status === 'approved') alert-success
                                            @elseif($verification->status === 'pending') alert-warning
                                            @else alert-danger @endif
                                        ">
                                                <p><strong>স্ট্যাটাস:</strong> <span class="text-capitalize fw-bold">{{ $verification->status }}</span></p>
                                                @if($verification->status === 'rejected')
                                                    <p><strong>বাতিলের কারণ:</strong> {{ $verification->rejection_reason }}</p>
                                                    <a href="{{ route('identity.verification') }}" class="btn btn-sm btn-primary mt-2">পুনরায় আবেদন করুন</a>
                                                @elseif($verification->status === 'pending')
                                                    <p>আপনার আবেদনটি পর্যালোচনার অধীনে আছে।</p>
                                                @else
                                                    <p>আপনার পরিচয় সফলভাবে যাচাই করা হয়েছে।</p>
                                                @endif
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <p>আপনার পরিচয় এখনো যাচাই করা হয়নি। বাসা ভাড়া নিতে বা পোস্ট করতে অনুগ্রহ করে আপনার পরিচয়পত্র জমা দিন।</p>
                                                <a href="{{ route('identity.verification') }}" class="btn btn-primary mt-2">এখনই যাচাই করুন</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </div>
@endsection
