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

    <!-- Begin Wrapper -->
    <div class="main-wrapper">

        <!-- Header Start -->
        @include('layouts.partials.header')
        <!-- Header End -->

        @yield('content')

        @include('layouts.partials.footer')

    </div>
    <!-- End Wrapper -->

    @include('includes.script')

    @livewireScripts

    @stack('scripts')
</body>
</html>
