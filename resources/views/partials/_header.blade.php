{{-- resources/views/partials/_header.blade.php --}}
<div class="main-header-two">
    <!-- Header Start -->
    <header class="header header-two">
        <div class="container">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a href="{{ route('home') }}" class="navbar-brand logo">
                        <img src="{{ $headerSettings->light_logo ? Storage::url($headerSettings->light_logo) : asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a href="{{ route('home') }}" class="navbar-brand logo-dark">
                        <img src="{{ $headerSettings->dark_logo ? Storage::url($headerSettings->dark_logo) : asset('assets/img/logo-white.svg') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a id="mobile_btn" href="javascript:void(0);">
                        <i class="material-icons-outlined">menu</i>
                    </a>
                </div>

                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="{{ route('home') }}" class="menu-logo">
                            <img src="{{ $headerSettings->light_logo ? Storage::url($headerSettings->light_logo) : asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                        </a>
                        <a href="{{ route('home') }}" class="menu-logo menu-logo-dark">
                            <img src="{{ $headerSettings->dark_logo ? Storage::url($headerSettings->dark_logo) : asset('assets/img/logo-white.svg') }}" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="material-icons-outlined">close</i>
                        </a>
                    </div>

                    {{-- ========== START: ডাইনামিক মাল্টি-লেভেল মেন্যু ========== --}}
                    <ul class="main-nav">
                        @foreach($menuItems as $menuItem)
                            {{-- আমরা এখানে বলে দিচ্ছি যে এটি লেভেল ১ --}}
                            <x-layouts.menu-item :item="$menuItem" :level="1" />
                        @endforeach
                    </ul>
                    {{-- ========== END: ডাইনামিক মাল্টি-লেভেল মেন্যু ========== --}}

                    {{-- The rest of the mobile menu can remain static as per your design --}}
                    <div class="menu-login">
                        @guest
                            {{-- গেস্ট ব্যবহারকারীর জন্য --}}
                            <a href="{{ route('filament.app.auth.login') }}" class="btn btn-primary w-100 mb-2">{{ $headerSettings->signin_button_text ?? 'Sign In' }}</a>
                            <a href="{{ route('filament.app.auth.register') }}" class="btn btn-secondary w-100">{{ $headerSettings->register_button_text ?? 'Register' }}</a>
                        @else
                            {{-- লগইন করা ব্যবহারকারীর জন্য --}}
                            {{-- ডেস্কটপের ড্রপডাউন মেন্যুর মতোই একটি ড্রপডাউন এখানে যোগ করা হয়েছে --}}
                            <div class="dropdown">
                                {{-- ড্রপডাউন টগল বাটন --}}
                                <a href="javascript:void(0);" class="btn btn-primary dropdown-toggle w-100" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                {{-- ড্রপডাউন মেন্যু আইটেম --}}
                                <div class="dropdown-menu dropdown-menu-end w-100">
                                    <a href="{{ route('filament.app.pages.dashboard') }}" class="dropdown-item">আমার প্রোফাইল</a>
                                    <a href="{{ route('wishlist') }}" class="dropdown-item">পছন্দের প্রপার্টি</a>
                                    <a href="{{ route('identity.verification') }}" class="dropdown-item">পরিচয় যাচাইকরণ</a>
                                    <a href="javascript:void(0);" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">লগ আউট</a>
                                    {{-- আইডি পরিবর্তন করা হয়েছে যাতে ডেস্কটপের সাথে conflict না করে --}}
                                    <form id="mobile-logout-form" action="{{ route('filament.app.auth.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>

                <div class="nav header-items">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="topbar-link btn btn-light" data-bs-toggle="dropdown">
                            <i class="material-icons-outlined">wb_sunny</i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" id="light-mode-toggle">
                                <i class="material-icons-outlined me-2">wb_sunny</i> <span class="align-middle">Light Mode</span>
                            </a>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" id="dark-mode-toggle">
                                <i class="material-icons-outlined me-2">dark_mode</i> <span class="align-middle">Dark Mode</span>
                            </a>
                        </div>
                    </div>

                    {{-- Dynamic Login/Register Buttons --}}
                    @guest
                        <a href="{{ route('filament.app.auth.login') }}" class="btn btn-lg btn-primary d-inline-flex align-items-center">
                            <i class="material-icons-outlined me-1">lock</i>{{ $headerSettings->signin_button_text ?? 'Sign In' }}
                        </a>
                        <a href="{{ route('filament.app.auth.register') }}" class="btn btn-lg btn-dark d-inline-flex align-items-center">
                            <i class="material-icons-outlined me-1">perm_identity</i>{{ $headerSettings->register_button_text ?? 'Register' }}
                        </a>
                    @else
                        {{-- Logged in user dropdown --}}
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-lg btn-primary dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route('filament.app.pages.dashboard') }}" class="dropdown-item">আমার প্রোফাইল</a>
                                <a href="{{ route('wishlist') }}" class="dropdown-item">পছন্দের প্রোপার্টি</a>
                                <a href="{{ route('identity.verification') }}" class="dropdown-item">পরিচয় যাচাইকরণ</a>
                                <a href="javascript:void(0);" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">লগ আউট</a>
                                <form id="logout-form" action="{{ route('filament.app.auth.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </nav>
        </div>
    </header>
    <!-- Header End -->
</div>
