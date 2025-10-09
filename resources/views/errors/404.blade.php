@extends('layouts.app')

@section('title', 'Error | '. config('app.name'))

@section('content')

    <!-- Start Content -->
    <div class="container-fuild">
        <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 z-1">
            <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap ">
                <div class="col-lg-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="error-images mb-4">
                            <img src="{{ asset('assets/img/error/error-404.svg') }}" alt="image" class="img-fluid">
                        </div>
                        <div class="text-center">
                            <h4 class="mb-2">Oops! Page Not Found!</h4>
                            <p class="text-center">The page you requested was not found.</p>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('home') }}" class="btn btn-dark d-flex align-items-center">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

@endsection
