@extends('layouts.app')

@section('title', 'My Properties | '. config('app.name'))

@section('content')
    <div class="page-wrapper">
        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">প্রোপার্টিসমূহ</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">আমার প্রোপার্টিসমূহ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="content">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>আমার প্রোপার্টিসমূহ</h3>
                    <a href="{{ route('property.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> নতুন প্রোপার্টি যোগ করুন
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @forelse ($properties as $property)
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <img src="{{ $property->getFirstMediaUrl('thumbnail', 'thumb') ?: 'https://via.placeholder.com/300x200' }}" class="img-fluid rounded-start h-100" alt="{{ $property->title }}">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">{{ $property->title }}</h5>
                                        <div>
                                        <span class="badge
                                            @if($property->status == 'active') bg-success
                                            @elseif($property->status == 'pending') bg-warning
                                            @else bg-danger @endif">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                        </div>
                                    </div>
                                    <p class="card-text"><small class="text-muted">{{ $property->address_area }}, {{ $property->address_street }}</small></p>
                                    <p class="card-text"><strong>মূল্য:</strong> ৳{{ number_format($property->rent_price) }}</p>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                        <a href="{{ route('property.edit', $property) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-1"></i> এডিট
                                        </a>
                                        {{-- ডিলিট বাটন (ঐচ্ছিক) --}}
                                        <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই প্রোপার্টিটি মুছে ফেলতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i> ডিলিট
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <p>আপনি এখনো কোনো প্রোপার্টি যোগ করেননি।</p>
                            <a href="{{ route('property.create') }}" class="btn btn-primary">প্রথম প্রোপার্টি যোগ করুন</a>
                        </div>
                    </div>
                @endforelse

                {{-- পেজিনেশন লিংক --}}
                <div class="mt-4">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
