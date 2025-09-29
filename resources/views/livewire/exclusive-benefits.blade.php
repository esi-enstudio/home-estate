<section class="exclusive-benifit-section">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title-2" data-aos="fade-up" data-aos-duration="500">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>আবিষ্কার করুন আমাদের <span>বিশেষ সুবিধা</span> এবং অনন্য বৈশিষ্ট্য</h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>আমাদের সাথে আপনার প্রপার্টি খোঁজার অভিজ্ঞতা হবে সেরা, কারণ আমরা দিচ্ছি নির্ভরযোগ্য সব সেবা।</p>
        </div>
        <!-- Section Title End -->

        @if($benefits->isNotEmpty())
            <!-- start row -->
            <div class="row">
                @foreach($benefits as $benefit)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="{{ 1000 + ($loop->index * 500) }}">
                        <div class="benifit-item">
                            <span class="benifit-icon">
                                <i class="material-icons-outlined">{{ $benefit->icon_class }}</i>
                            </span>
                            <div>
                                <h5 class="mb-2">{{ $benefit->title_bn }}</h5>
                                <p class="mb-0">{{ $benefit->description_bn }}</p>
                            </div>
                        </div>
                    </div> <!-- end col -->
                @endforeach
            </div>
            <!-- end row -->
        @endif

        <div class="sec-bottom-imgs">
            <div class="bottom-img-1"><img src="{{ asset('assets/img/benifits/benifit-01.jpg') }}" alt="Benefit Image"></div>
            <div class="bottom-img-2"><img src="{{ asset('assets/img/benifits/benifit-02.jpg') }}" alt="Benefit Image"></div>
            <div class="bottom-img-3"><img src="{{ asset('assets/img/benifits/benifit-03.jpg') }}" alt="Benefit Image"></div>
            <div class="bottom-img-4"><img src="{{ asset('assets/img/benifits/benifit-04.jpg') }}" alt="Benefit Image"></div>
            <div class="bottom-img-5"><img src="{{ asset('assets/img/benifits/benifit-05.jpg') }}" alt="Benefit Image"></div>
            <div class="bottom-img-6"><img src="{{ asset('assets/img/benifits/benifit-06.jpg') }}" alt="Benefit Image"></div>
        </div>
    </div>
</section>
