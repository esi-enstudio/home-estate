<section class="banner-section-three aos">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xxl-6 col-lg-7">
                <div class="banner-content" data-aos="fade-up">
                    <div class="banner-badge d-inline-flex align-items-center">
                        <span class="badge bg-warning me-2">নতুন</span>
                        <p class="mb-0">{{ $bannerSettings->badge_text ?? 'দেশের নং ১ রিয়েল এস্টেট ওয়েবসাইট' }}</p>
                    </div>
                    <h1>
                        {{ $bannerSettings->title_main ?? 'আপনার স্বপ্নের বাড়ি' }}
                        @if(!empty($bannerSettings->title_highlighted))
                            <span>{{ $bannerSettings->title_highlighted }}</span>
                        @endif
                    </h1>

                    <p>{{ $bannerSettings->subtitle ?? 'আপনার আশেপাশেই রয়েছে ৩০০০ এর বেশি প্রপার্টি লিস্টিং।' }}</p>
                    <a href="{{ $bannerSettings->button_link ?? '#' }}" class="btn btn-primary"><i class="material-icons-outlined me-2">add_business</i>{{ $bannerSettings->button_text ?? 'আপনার প্রপার্টি যোগ করুন' }}</a>
                </div>
            </div>

            @if($bannerProperty)
                <div class="col-xxl-4 col-lg-5">
                    <div class="banner-right-content">
                        <div class="banner-card">
                            <div class="me-3 card-img">
                                <a href="{{ route('properties.show', $bannerProperty) }}"><img src="{{ $bannerProperty->getFirstMediaUrl('featured_image', 'thumbnail') ?: 'https://placehold.co/150x150' }}" class="rounded" alt="{{ $bannerProperty->title }}"></a>
                            </div>
                            <div>
                                <h6 class="text-white"><a href="{{ route('properties.show', $bannerProperty) }}" class="text-white">{{ Str::limit($bannerProperty->title, 20) }}</a></h6>
                                <span class="text-white mb-1 d-block">{{ $bannerProperty->address_area }}</span>
                                <p class="rate-info mb-3"><span>৳{{ number_format($bannerProperty->rent_price) }}</span> /মাসিক</p>
                                <div class="d-flex align-items-center card-info">
                                    <p class="me-3"><span class="me-2"><i class="material-icons-outlined">bed</i></span>{{ $bannerProperty->bedrooms }} বেড</p>
                                    <p><span class="me-2"><i class="material-icons-outlined">bathtub</i></span>{{ $bannerProperty->bathrooms }} বাথ</p>
                                </div>
                            </div>
                        </div>

                        @if($recentReviewers->isNotEmpty())
                            <div class="banner-users d-flex align-items-center flex-wrap gap-2 mb-3">
                                <div class="avatar-list-stacked">
                                    @foreach($recentReviewers as $reviewer)
                                        <span class="avatar avatar-md rounded-circle border-0" title="{{ $reviewer->name }}">
                                            <img src="{{ \Storage::url($reviewer->avatar_url) ?? 'https://placehold.co/100' }}" class="img-fluid rounded-circle" alt="{{ $reviewer->name }}">
                                        </span>
                                    @endforeach
                                </div>
                                <div>
                                    @if($ratingStats['count'] > 0)
                                        <div class="d-flex align-items-center mb-1">
                                            <h6 class="mb-0 me-2 text-white fw-semibold fs-14">গড় রেটিং {{ number_format($ratingStats['average'], 1) }}</h6>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="material-icons-outlined text-warning" style="font-size: 1rem;">{{ $i <= round($ratingStats['average']) ? 'star' : 'star_border' }}</i>
                                            @endfor
                                        </div>
                                        <p class="mb-0 text-white fs-13">{{ $ratingStats['count'] }}+ এর বেশি গ্রাহকের আস্থা</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
