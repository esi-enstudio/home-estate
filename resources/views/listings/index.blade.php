@extends('layouts.app')

@section('title', 'My Listings | '. config('app.name'))

@section('content')
    <div class="page-wrapper">

        <!-- Start Breadcrumb -->
        <div class="breadcrumb-bar">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
            <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
            <div class="row align-items-center text-center position-relative z-1">
                <div class="col-md-12 col-12 breadcrumb-arrow">
                    <h1 class="breadcrumb-title">আমার লিস্টিংসমূহ</h1>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>হোম</a></li>
                            <li class="breadcrumb-item active" aria-current="page">আমার লিস্টিং</li>
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
                        {{-- নতুন লিস্টিং তৈরির বাটন --}}
                        <div class="d-flex justify-content-end mb-4">
                            <a href="{{ route('listings.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>নতুন লিস্টিং যোগ করুন</a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="cart-item-wrap">
                            @if($properties->isEmpty())
                                <div class="text-center py-5">
                                    <p>আপনি এখনো কোনো লিস্টিং যোগ করেননি।</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                        <tr>
                                            <th>শিরোনাম</th>
                                            <th>ধরন</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>মাসিক ভাড়া</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($properties as $property)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ Str::limit($property->title, 40) }}</div>
                                                    <small class="text-muted">{{ $property->property_code }}</small>
                                                </td>
                                                <td>{{ $property->propertyType->name }}</td>
                                                <td><span class="badge bg-primary text-capitalize">{{ $property->status }}</span></td>
                                                <td>৳{{ number_format($property->rent_price) }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('listings.edit', $property) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-edit me-1"></i> এডিট
                                                    </a>
                                                    <form action="{{ route('listings.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি এই লিস্টিংটি মুছে ফেলতে নিশ্চিত?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash me-1"></i> ডিলিট
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $properties->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->

    </div>
@endsection
