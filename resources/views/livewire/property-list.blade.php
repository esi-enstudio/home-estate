<div class="page-wrapper">

    <!-- Start Breadscrumb -->
    <div class="breadcrumb-bar">
        <img src="{{ asset('assets/img/bg/breadcrumb-bg-01.png') }}" alt="" class="breadcrumb-bg-01 d-none d-lg-block">
        <img src="{{ asset('assets/img/bg/breadcrumb-bg-02.png') }}" alt="" class="breadcrumb-bg-02 d-none d-lg-block">
        <img src="{{ asset('assets/img/bg/breadcrumb-bg-03.png') }}" alt="" class="breadcrumb-bg-03">
        <div class="row align-items-center text-center position-relative z-1">
            <div class="col-md-12 col-12 breadcrumb-arrow">
                <h1 class="breadcrumb-title">Rent Properties</h1>
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><span><i class="material-icons-outlined me-1">home</i></span>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rent Properties</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- End Breadscrumb -->

    <!-- Start Content -->
    <div class="content">
        <div class="container">
            <div class="card border-0 search-item mb-4">
                <div class="card-body">

                    <!-- Sorting and View Toggle Row -->
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <p class="mb-4 mb-lg-0 mb-md-3 text-lg-start text-md-start text-center">
                                Showing result
                                <span class="result-value">{{ count($properties) }}</span>
                                of
                                <span class="result-value">{{ $totalPropertiesCount }}</span>
                            </p>
                        </div> <!-- end col -->

                        <div class="col-lg-9">
                            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-lg-end flex-lg-row flex-md-row flex-column">
                                <div class="result-list d-flex d-block flex-lg-row flex-md-row flex-column align-items-center gap-2">
                                    <h5>Sort By</h5>
                                    <div class="result-select">
                                        {{-- wire:model এর মাধ্যমে sortBy প্রোপার্টির সাথে বাইন্ড করা হলো --}}
                                        <select class="form-select" wire:model.live="sortBy">
                                            <option value="default">Default</option>
                                            <option value="title_asc">A-Z</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="result-list d-flex flex-lg-row flex-md-row flex-column align-items-center gap-2">
                                    <h5>Price Range</h5>
                                    <div class="result-select">
                                        {{-- wire:model এর মাধ্যমে sortPrice প্রোপার্টির সাথে বাইন্ড করা হলো --}}
                                        <select class="form-select" wire:model.live="sortPrice">
                                            <option value="default">Default</option>
                                            <option value="lth">Low to High</option>
                                            <option value="htl">High to Low</option>
                                        </select>
                                    </div>
                                </div>

                                <ul class="grid-list-view d-flex align-items-center justify-content-center">
                                    {{-- wire:click.prevent দিয়ে setViewMode মেথড কল করা হলো --}}
                                    <li>
                                        <a href="javascript:void(0)" wire:click.prevent="setViewMode('list')" class="list-icon {{ $viewMode === 'list' ? 'active' : '' }}">
                                            <i class="material-icons">list</i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" wire:click.prevent="setViewMode('grid')" class="list-icon {{ $viewMode === 'grid' ? 'active' : '' }}">
                                            <i class="material-icons">grid_view</i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div>
            </div> <!-- end card -->

            <!-- start row -->
            <div class="row">

                <!-- sidebar filter -->
                <div class="col-xl-4 theiaStickySidebar">
                    <div class="filter-sidebar rent-grid-sidebar-item-02 mb-xl-0">
                        <div class="filter-head d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Filter</h5>
                            {{-- wire:click.prevent রিসেট করবে --}}
                            <a href="javascript:void(0)" wire:click.prevent="resetFilters" class="text-danger">Reset</a>
                        </div>
                        <div class="filter-body">

                            <!-- Items -->
                            <div class="filter-set">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex justify-content-between w-100 filter-search-head" data-bs-toggle="collapse" data-bs-target="#search" aria-expanded="false" role="button">
                                        <h6 class="d-inline-flex align-items-center mb-0"><i class="material-icons-outlined me-2 text-secondary">search</i>Search</h6>
                                        <i class="material-icons-outlined expand-arrow">expand_less</i>
                                    </div>
                                </div>
                                <div id="search" class="card-collapse collapse show mt-3">
                                    {{-- wire:model.defer ডেটা বাইন্ড করবে কিন্তু রিকোয়েস্ট পাঠাবে না --}}
                                    <div class="input-group input-group-flat mb-3">
                                        <span class="input-group-text border-0"><i class="material-icons-outlined">search</i></span>
                                        <input type="text" wire:model.defer="search" class="form-control" placeholder="Search by title, area...">
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label mb-1">Purpose</label>
                                        <select wire:model.defer="purpose" class="form-select">
                                            <option value="">Any</option>
                                            <option value="rent">Rent</option>
                                            <option value="sell">Sell</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label mb-1">Rent Type</label>
                                        <select wire:model.defer="rent_type" class="form-select">
                                            <option value="">Any</option>
                                            <option value="day">Day</option>
                                            <option value="week">Week</option>
                                            <option value="month">Month</option>
                                            <option value="year">Year</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label mb-1">Negotiable</label>
                                        <select wire:model.defer="is_negotiable" class="form-select">
                                            <option value="">Any</option>
                                            <option value="negotiable">Negotiable</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label mb-1">Bedrooms</label>
                                        <select wire:model.defer="bedrooms" class="form-select">
                                            <option value="">Any</option>
                                            @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label mb-1">Bathrooms</label>
                                        <select wire:model.defer="bathrooms" class="form-select">
                                            <option value="">Any</option>
                                            @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label mb-1"> Min Sqft </label>
                                        <div class="input-group input-group-flat mb-0">
                                            <input type="number" wire:model.defer="min_sqft" class="form-control" placeholder="e.g. 1200">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="filter-footer">
                            {{-- wire:click.prevent ফিল্টার প্রয়োগ করবে --}}
                            <button wire:click.prevent="applyFilter" class="btn btn-dark w-100">
                                <span wire:loading.remove wire:target="applyFilter">Apply Filter</span>
                                <span wire:loading wire:target="applyFilter">Searching...</span>
                            </button>
                        </div>
                    </div>
                </div>  <!-- end sidebar filter -->

                <div class="col-xl-8">
                    <!-- start row -->
                    <div class="row mb-4">

                        @forelse($properties as $property)

                            {{-- ====================================================== --}}
                            {{--                ভিউ মোডের উপর ভিত্তি করে রেন্ডারিং             --}}
                            {{-- ====================================================== --}}
                            @php
                                // প্রথমে মিডিয়া অবজেক্টটি একটি ভ্যারিয়েবলে নিন
                                $featuredImage = $property->getFirstMedia('featured_image');
                            @endphp

                            @if ($viewMode === 'grid')

                                {{-- ########## START: GRID VIEW ITEM ########## --}}
                                <div class="col-lg-6 col-md-6 d-flex">
                                <div class="property-card flex-fill">
                                    <div class="property-listing-item p-0 mb-0 shadow-none">
                                        <div class="buy-grid-img mb-0 rounded-0">
                                            {{-- এখানে সিঙ্গেল প্রপার্টি পেজের লিঙ্ক যুক্ত করতে হবে --}}
                                            <a href="{{ route('properties.show', $property) }}">
                                                @if ($featuredImage)
                                                    {{-- যদি ছবি থাকে, তাহলে WebP থাম্বনেইলটি দেখাও --}}
                                                    <img class="img-fluid" src="{{ $property->getFirstMediaUrl('featured_image', 'thumbnail') }}" alt="{{ $property->title }}">
                                                @else
                                                    {{-- যদি কোনো ছবি না থাকে, তাহলে placeholder দেখাও --}}
                                                    <img class="img-fluid" src="https://placehold.co/832x472" alt="{{ $property->title }}">
                                                @endif
                                            </a>
                                            <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3 z-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    {{-- গত ৭ দিনের মধ্যে তৈরি হলে "New" ব্যাজ দেখাবে --}}
                                                    @if($property->created_at->gt(now()->subDays(7)))
                                                        <div class="badge badge-sm bg-danger d-flex align-items-center">
                                                            <i class="material-icons-outlined">offline_bolt</i>New
                                                        </div>
                                                    @endif

                                                    @if($property->is_featured)
                                                        <div class="badge badge-sm bg-orange d-flex align-items-center">
                                                            <i class="material-icons-outlined">loyalty</i>Featured
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3 z-1">
                                                <h6 class="text-white mb-0">৳{{ number_format($property->rent_price) }} <span class="fs-14 fw-normal"> / {{ ucfirst($property->rent_type) }} </span></h6>
                                                <a href="#" wire:click.prevent="toggleFavorite({{ $property->id }})" class="favourite">
                                                    @if(in_array($property->id, $favoritedPropertyIds))
                                                        {{-- যদি ফেভারিট করা থাকে --}}
                                                        <i class="material-icons-outlined text-danger">favorite</i>
                                                    @else
                                                        {{-- যদি ফেভারিট করা না থাকে --}}
                                                        <i class="material-icons-outlined">favorite_border</i>
                                                    @endif
                                                </a>
                                            </div>
                                        </div>

                                        <div class="buy-grid-content">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    {{-- এখানে রেটিং সিস্টেমের লজিক যুক্ত করতে হবে --}}
                                                    <i class="material-icons-outlined text-warning">star</i>
                                                    <span class="ms-1 fs-14">{{ $property->average_rating }} ({{ $property->reviews_count }} reviews)</span>
                                                </div>
                                                <span class="badge bg-secondary">{{ $property->propertyType->name_en }}</span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div>
                                                    <h6 class="title mb-1">
                                                        <a href="{{ route('properties.show', $property) }}">{{ $property->title }}</a>
                                                    </h6>
                                                    <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1 ms-0">location_on</i>{{ $property->address_area }}, {{ $property->district->name }}</p>
                                                </div>
                                            </div>
                                            <ul class="d-flex buy-grid-details d-flex mb-3 bg-light rounded p-3 justify-content-between align-items-center flex-wrap gap-1">
                                                <li class="d-flex align-items-center gap-1">
                                                    <i class="material-icons-outlined bg-white text-secondary">bed</i>
                                                    {{ $property->bedrooms }} Bedroom
                                                </li>
                                                <li class="d-flex align-items-center gap-1">
                                                    <i class="material-icons-outlined bg-white text-secondary">bathtub</i>
                                                    {{ $property->bathrooms }} Bath
                                                </li>
                                                <li class="d-flex align-items-center gap-1">
                                                    <i class="material-icons-outlined bg-white text-secondary">straighten</i>
                                                    {{ $property->size_sqft }} Sq Ft
                                                </li>
                                            </ul>
                                            <div class="d-flex align-items-center justify-content-between flex-wrap border-top border-light-100 pt-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar avatar-lg user-avatar">
                                                        {{-- এখানে ব্যবহারকারীর প্রোফাইল ছবি যুক্ত করতে হবে --}}
                                                        <img src="{{ $property->user->avatar_url ?? asset('assets/img/user-default-avatar.png') }}" alt="{{ $property->user->name }}" class="rounded-circle">
                                                    </div>
                                                    <h6 class="mb-0 fs-16 fw-medium text-dark">{{ $property->user->name }}<span class="d-block fs-14 text-body pt-1">Owner</span> </h6>
                                                </div>
                                                <a href="{{ route('properties.show', $property) }}" class="btn btn-dark">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                                {{-- ########## END: GRID VIEW ITEM ########## --}}

                            @else

                                {{-- ########## START: LIST VIEW ITEM ########## --}}
                                <div class="col-lg-12 col-md-12 d-flex"> {{-- লিস্ট ভিউতে এটি সম্পূর্ণ প্রস্থ নেবে --}}
                                    <div class="property-card flex-fill">
                                        <div class="property-listing-item p-0 mb-0 shadow-none d-flex flex-lg-nowrap flex-wrap">
                                            <div class="buy-grid-img buy-list-img rent-list-img mb-0 rounded-0">
                                                <a href="{{ route('properties.show', $property) }}">
                                                    @if ($featuredImage)
                                                        {{-- যদি ছবি থাকে, তাহলে WebP থাম্বনেইলটি দেখাও --}}
                                                        <img class="img-fluid" src="{{ $property->getFirstMediaUrl('featured_image', 'thumbnail') }}" alt="{{ $property->title }}">
                                                    @else
                                                        {{-- যদি কোনো ছবি না থাকে, তাহলে placeholder দেখাও --}}
                                                        <img class="img-fluid" src="https://placehold.co/832x472" alt="{{ $property->title }}">
                                                    @endif
                                                </a>

                                                <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3 z-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($property->created_at->gt(now()->subDays(7)))
                                                            <div class="badge badge-sm bg-danger d-flex align-items-center">
                                                                <i class="material-icons-outlined">offline_bolt</i>New
                                                            </div>
                                                        @endif
                                                        @if($property->is_featured)
                                                            <div class="badge badge-sm bg-orange d-flex align-items-center">
                                                                <i class="material-icons-outlined">loyalty</i>Featured
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3 z-1">
                                                    <h6 class="text-white mb-0">৳{{ number_format($property->rent_price) }} <span class="fs-14 fw-normal"> / {{ ucfirst($property->rent_type) }} </span></h6>
                                                    <a href="javascript:void(0)" class="favourite"><i class="material-icons-outlined">favorite_border</i></a>
                                                </div>
                                            </div>
                                            <div class="buy-grid-content w-100">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <i class="material-icons-outlined text-warning">star</i>
                                                        <span class="ms-1 fs-14">{{ $property->average_rating }} ({{ $property->reviews_count }} reviews)</span>
                                                    </div>
                                                    <span class="badge bg-secondary">{{ $property->propertyType->name_en }}</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div>
                                                        <h6 class="title mb-1"><a href="{{ route('properties.show', $property) }}">{{ $property->title }}</a></h6>
                                                        <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1 ms-0">location_on</i>{{ $property->address_area }}, {{ $property->district->name }}</p>
                                                    </div>
                                                </div>
                                                <ul class="d-flex buy-grid-details d-flex mb-3 bg-light rounded p-3 justify-content-between align-items-center flex-wrap gap-1">
                                                    <li class="d-flex align-items-center gap-1"><i class="material-icons-outlined bg-white text-secondary">bed</i> {{ $property->bedrooms }} Bedroom</li>
                                                    <li class="d-flex align-items-center gap-1"><i class="material-icons-outlined bg-white text-secondary">bathtub</i> {{ $property->bathrooms }} Bath</li>
                                                    <li class="d-flex align-items-center gap-1"><i class="material-icons-outlined bg-white text-secondary">straighten</i> {{ $property->size_sqft }} Sq Ft</li>
                                                </ul>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap border-top border-light-100 pt-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar avatar-lg user-avatar">
                                                            <img src="{{ $property->user->avatar_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $property->user->name }}" class="rounded-circle">
                                                        </div>
                                                        <h6 class="mb-0 fs-16 fw-medium text-dark">{{ $property->user->name }}<span class="d-block fs-14 text-body pt-1">Owner</span></h6>
                                                    </div>
                                                    <a href="#" class="btn btn-dark">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- ########## END: LIST VIEW ITEM ########## --}}

                            @endif

                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <h4>No Properties Found!</h4>
                                    <p>We couldn't find any properties matching your criteria.</p>
                                </div>
                            </div>
                        @endforelse

                    </div>
                    <!-- end row -->

                    {{-- আরও প্রপার্টি থাকলে "Load More" বাটনটি দেখানো হবে --}}
                    @if($hasMorePages)
                        <div class="text-center">
                            <button wire:click="loadMore" wire:loading.attr="disabled" class="btn btn-dark d-inline-flex align-items-center">
                                <span wire:loading.remove wire:target="loadMore">
                                    <i class="material-icons-outlined me-1">autorenew</i>Load More
                                </span>

                                {{-- লোড হওয়ার সময় এই লেখাটি দেখাবে --}}
                                <span wire:loading wire:target="loadMore">
                                    Loading...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- End Content -->
</div>
