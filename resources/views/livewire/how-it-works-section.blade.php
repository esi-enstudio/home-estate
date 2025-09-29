<section class="work-section">
    <div class="container">
        <div class="row align-items-center justify-content-lg-end">
            <div class="col-lg-6">
                <!-- Section Title Start -->
                <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
                    <div class="d-flex align-items-center mb-3">
                        <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                        <span class="text-white d-inline-block ms-2">আপনার প্রপার্টি বিজ্ঞাপন দিন</span>
                    </div>
                    <h2>আপনার প্রপার্টি বিক্রয় বা ভাড়ার জন্য আকর্ষণীয় সমাধান</h2>
                    <p>বিক্রি হোক বা ভাড়া, আমরা আপনার প্রপার্টির বিজ্ঞাপন সঠিক গ্রাহকের কাছে পৌঁছে দিই। আমাদের আধুনিক টুলস এবং বিশেষজ্ঞের সহায়তায় আপনার কাজ হবে আরও সহজ এবং দ্রুত।</p>
                    <a href="#" class="btn btn-primary">বিনামূল্যে বিজ্ঞাপন দিন</a>
                </div>
                <!-- Section Title End -->
            </div>

            @if($steps->isNotEmpty())
                <div class="col-lg-6">
                    <div class="card work-item border-0 mb-0">
                        <div class="card-body">
                            <div class="mb-4">
                                <span class="badge bg-secondary mb-2">কার্যপ্রণালী</span>
                                <h2>আপনার স্বপ্নের ঠিকানা খুঁজে দিন সহজেই</h2>
                            </div>

                            @foreach($steps as $step)
                                <div class="work-steps @if($loop->last) mb-0 @endif">
                                    <h6 class="fw-semibold fs-16 mb-1 {{ $step->color_class }}">
                                        {{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}. {{ $step->title_bn }}
                                    </h6>
                                    <p class="mb-0 fs-14">{{ $step->description_bn }}</p>
                                </div>
                            @endforeach

                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            @endif
        </div>
    </div>
</section>
