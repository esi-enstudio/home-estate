<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
          content="Dreams Estate is a powerful real estate template for property listings, rentals, and agency dashboards. Built with HTML, React, Vue, Angular, and Laravel. Ideal for property portals and real estate platforms.">
    <meta name="keywords"
          content="real estate template, property management, real estate dashboard, property listings, rental template, agency admin, HTML real estate, React real estate, Vue dashboard, Angular real estate, Laravel property UI">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ENN Creation">

    @include('includes.head')

    @livewireStyles
</head>

<body>

@php
    // একটি ভ্যারিয়েবলে স্টাইল স্ট্রিং তৈরি করা হচ্ছে, ডিফল্টভাবে খালি থাকবে
    $mainWrapperStyle = '';

    // কন্ডিশন: যদি বর্তমান পেজের URL '/coming-soon' হয়
    if (request()->is('coming-soon')) {
        // SiteSettings থেকে الخلفية ছবিটি লোড করা হচ্ছে
        $settings = app(\App\Settings\MaintenanceSettings::class);

        // যদি অ্যাডমিন প্যানেল থেকে 'auth_background_image' সেট করা থাকে
        if ($settings->background_image) {
            $imageUrl = \Illuminate\Support\Facades\Storage::url($settings->background_image);
            $mainWrapperStyle = "background-image: url('{$imageUrl}'); background-size: cover; background-position: center;";
        }
    }
@endphp

    <!-- Begin Wrapper -->
    {{--
        ডাইনামিকভাবে তৈরি করা স্টাইল স্ট্রিংটি এখানে প্রিন্ট করা হচ্ছে।
        যদি অন্য কোনো পেজ হয়, তাহলে style অ্যাট্রিবিউটটি খালি থাকবে।
    --}}
    <div class="main-wrapper" style="{{ $mainWrapperStyle }}">

        <!-- Header Start -->
        @include('partials._header')
        <!-- Header End -->

        @yield('content')

        @include('partials._footer')

    </div>
    <!-- End Wrapper -->

    @include('includes.script')

    @livewireScripts

    @stack('scripts')
</body>
</html>
