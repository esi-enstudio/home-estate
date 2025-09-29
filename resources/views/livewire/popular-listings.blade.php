<section class="popular-listing-section">
    <div class="container">
        <!-- Section Title Start -->
        <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>জনপ্রিয় <span>প্রপার্টি</span> আবিষ্কার করুন</h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>আপনার স্বপ্নের বাড়ি কেনার জন্য প্রস্তুত? এখানেই খুঁজে নিন</p>
        </div>
        <!-- Section Title End -->

        <ul class="nav nav-pills listing-nav-2" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $purpose === 'rent' ? 'active' : '' }}" href="#" wire:click.prevent="setPurpose('rent')" role="tab">
                    ভাড়ার জন্য
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $purpose === 'sell' ? 'active' : '' }}" href="#" wire:click.prevent="setPurpose('sell')" role="tab">
                    বিক্রির জন্য
                </a>
            </li>
        </ul>

        <div class="tab-content" wire:loading.class="opacity-50" wire:target="setPurpose">
            <div class="tab-pane fade active show">
                <div class="row">
                    @forelse($properties as $property)
                        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                            {{-- পুনঃব্যবহারযোগ্য প্রপার্টি কার্ডটি এখানে অন্তর্ভুক্ত করা হলো --}}
                            @include('partials._property-card', ['property' => $property])
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h5>দুঃখিত, এই মুহূর্তে কোনো প্রপার্টি পাওয়া যায়নি।</h5>
                        </div>
                    @endforelse

                    <div class="col-md-12">
                        <div class="text-center pt-3">
                            <a href="{{ route('properties.index') }}" class="btn btn-dark d-inline-flex align-items-center">
                                সকল প্রপার্টি দেখুন<i class="material-icons-outlined ms-1">north_east</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
