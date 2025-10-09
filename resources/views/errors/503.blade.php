{{-- resources/views/errors/503.blade.php --}}
@php
    // ভিউ কম্পোজার এখানে কাজ নাও করতে পারে, তাই সরাসরি সেটিংস লোড করা হচ্ছে
    $settings = app(\App\Settings\MaintenanceSettings::class);
@endphp

{{-- আপনার লেআউট ফাইলটিকে এখানে extend করতে পারেন, অথবা একটি স্বতন্ত্র পেজ বানাতে পারেন --}}
@extends('layouts.app')

@section('title', 'Maintenance | '. config('app.name'))

@section('content')
    <div class="page-wrapper">
        <div class="container-fuild bg-light">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 z-1">
                <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap ">
                    <div class="col-lg-6">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="error-images mb-4">
                                <img src="{{ asset('assets/img/error/under-maintenance.svg') }}" alt="Maintenance" class="img-fluid">
                            </div>
                            <div class="text-center">
                                <h4 class="mb-2">{{ $settings->maintenance_title ?? 'সাইটটি বর্তমানে রক্ষণাবেক্ষণের জন্য বন্ধ আছে' }}</h4>
                                <p class="text-center mb-4">{{ $settings->maintenance_subtitle ?? 'অসুবিধার জন্য আমরা আন্তরিকভাবে দুঃখিত। আমরা খুব শীঘ্রই ফিরে আসছি।' }}</p>

                                @if(!empty($settings->social_links))
                                    <div class="d-flex align-items-center justify-content-center mb-4">
                                        @foreach($settings->social_links as $platform => $url)
                                            @if(!empty($url))
                                                <a href="{{ $url }}" class="btn btn-white rounded-circle p-2 d-inline-flex align-items-center justify-content-end border-0 me-2" target="_blank" rel="noopener noreferrer">
                                                    {{-- আমরা প্ল্যাটফর্মের নামের উপর ভিত্তি করে আইকন দেখাব --}}
                                                    <i class="fa-brands fa-{{ strtolower($platform) }}"></i>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
