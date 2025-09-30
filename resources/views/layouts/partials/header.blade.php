<div class="main-header-two">

    <!-- Header Start -->
    <header class="header header-two">
        <div class="container">

            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a href="{{ route('home') }}" class="navbar-brand logo">
                        <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a href="{{ route('home') }}" class="navbar-brand logo-dark">
                        <img src="{{ asset('assets/img/logo-white.svg') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a id="mobile_btn" href="javascript:void(0);">
                        <i class="material-icons-outlined">menu</i>
                    </a>
                </div>

                <div class="main-menu-wrapper">

                    <div class="menu-header">
                        <a href="{{ route('home') }}" class="menu-logo">
                            <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                        </a>
                        <a href="{{ route('home') }}" class="menu-logo menu-logo-dark">
                            <img src="{{ asset('assets/img/logo-white.svg') }}" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="material-icons-outlined">close</i>
                        </a>
                    </div>

                    <div class="mobile-search">
                        <input type="text" class="form-control form-control-lg" placeholder="Search">
                    </div>

                    <ul class="main-nav">
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">Home</a>
                        </li>

                        <li class="has-submenu {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                            <a href="javascript:void(0);">Listing <i  class="material-icons-outlined">expand_more</i></a>
                            <ul class="submenu">
{{--                                <li class="has-submenu">--}}
{{--                                    <a href="javascript:void(0);">Buy Property</a>--}}
{{--                                    <ul class="submenu">--}}
{{--                                        <li><a href="buy-property-grid.html">Buy Grid</a></li>--}}
{{--                                        <li><a href="buy-property-list.html">Buy List</a></li>--}}
{{--                                        <li><a href="buy-property-grid-sidebar.html">Buy Grid with Sidebar</a></li>--}}
{{--                                        <li><a href="buy-property-list-sidebar.html">Buy List with Sidebar</a></li>--}}
{{--                                        <li><a href="buy-grid-map.html">Buy Grid with map</a></li>--}}
{{--                                        <li><a href="buy-list-map.html">Buy List with map</a></li>--}}
{{--                                        <li><a href="buy-details.html">Buy Details</a></li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}

                                <li class="{{ request()->routeIs('properties.index') ? 'active' : '' }}">
                                    <a href="{{ route('properties.index') }}">Rent Property</a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
                            <a href="{{ route('blog.index') }}">Blog</a>
                        </li>

                        <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                            <a href="{{ route('about') }}">About Us</a>
                        </li>

                        <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact') }}">Contact Us</a>
                        </li>

                    </ul>

                    <div class="menu-dropdown">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Light
                            </a>
                            <ul class="dropdown-menu mt-2">
                                <li><a class="dropdown-item light-mode" href="javascript:void(0);">Light</a></li>
                                <li><a class="dropdown-item dark-mode" href="javascript:void(0);">Dark</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="menu-login">
                        <a href="signin.html" class="btn btn-primary w-100 mb-2">Sign In</a>
                        <a href="signup.html" class="btn btn-secondary w-100">Register</a>
                    </div>

                </div>

                <div class="nav header-items">

                    <a href="#" class="topbar-link btn btn-light topbar-search" data-bs-toggle="modal" data-bs-target="#search-modal">
                        <i class="material-icons-outlined">search</i>
                    </a>

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

                    <a href="signin.html" class="btn btn-lg btn-primary d-inline-flex align-items-center"><i class="material-icons-outlined me-1">lock</i>Sign In</a>

                    <a href="signup.html" class="btn btn-lg btn-dark d-inline-flex align-items-center"><i class="material-icons-outlined me-1">perm_identity</i>Register</a>

                </div>
            </nav>

        </div>
    </header>
    <!-- Header End -->

</div>
