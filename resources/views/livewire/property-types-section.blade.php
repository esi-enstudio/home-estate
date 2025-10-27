<section class="property-type-section">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title-2" data-aos="fade-up" data-aos-duration="1000">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>জনপ্রিয় প্রপার্টি <span>ক্যাটাগরি</span></h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>আপনার প্রয়োজন অনুযায়ী সেরা প্রপার্টি খুঁজে নিন আমাদের নির্বাচিত ক্যাটাগরি থেকে।</p>
        </div>
        <!-- Section Title End -->

        @if($propertyTypes->isNotEmpty())
            <!-- start row -->
            <div class="row">
                @foreach($propertyTypes as $type)
                    <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-duration="{{ 1000 + ($loop->index * 500) }}">
                        <div class="property-type-item">
                            <div class="property-img">
                                {{-- এই লিঙ্কে ক্লিক করলে ওই ক্যাটাগরির সকল প্রপার্টি দেখাবে --}}
                                <a href="{{ route('properties.index', ['type' => $type->slug]) }}">
                                    <img src="{{ $type->getFirstMediaUrl('property_type_image') ?: 'https://placehold.co/400x280' }}" class="w-100" alt="{{ $type->name_bn }}">
                                </a>
                                <a href="{{ route('properties.index', ['type' => $type->slug]) }}" class="overlay-arrow">
                                    <i class="material-icons-outlined">north_east</i>
                                </a>
                            </div>
                            <div class="text-center">
                                <h5 class="mb-1"><a href="{{ route('properties.index', ['type' => $type->slug]) }}">{{ $type->name_bn }}</a></h5>
                                <p class="fs-14 mb-0">{{ $type->properties_count }} টি প্রপার্টি রয়েছে</p>
                            </div>
                        </div>
                    </div> <!-- end col -->
                @endforeach
            </div>
            <!-- end row -->

            <div class="text-center pt-3">
                <a href="{{ route('property-types.index') }}" class="btn btn-dark d-inline-flex align-items-center">
                    সব ক্যাটাগরি দেখুন<i class="material-icons-outlined ms-1">north_east</i>
                </a>
            </div>
        @endif

    </div>
</section>
