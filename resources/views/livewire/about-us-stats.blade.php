<div class="row row-gap-4">
    <div class="col-md-6 col-lg-3">
        <div class="about-us-item-02">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/about-us/listing.svg') }}" alt="লিস্টিং" class="img-fluid me-3">
                <div>
                    <h4 class="mb-1">{{ number_format($listingsAdded) }}+</h4>
                    <p class="mb-0">பதிவுভুক্ত লিস্টিং</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->
    <div class="col-md-6 col-lg-3">
        <div class="about-us-item-02">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/about-us/agents.svg') }}" alt="এজেন্ট" class="img-fluid me-3">
                <div>
                    <h4 class="mb-1">{{ number_format($agentsListed) }}+</h4>
                    <p class="mb-0">বিশ্বস্ত এজেন্ট</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->
    <div class="col-md-6 col-lg-3">
        <div class="about-us-item-02">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/about-us/sales.svg') }}" alt="বিক্রয়" class="img-fluid me-3">
                <div>
                    <h4 class="mb-1">{{ number_format($salesCompleted) }}+</h4>
                    <p class="mb-0">সফল বিক্রয়</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->
    <div class="col-md-6 col-lg-3">
        <div class="about-us-item-02">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/about-us/users.svg') }}" alt="ব্যবহারকারী" class="img-fluid me-3">
                <div>
                    <h4 class="mb-1">{{ number_format($usersJoined) }}+</h4>
                    <p class="mb-0">সন্তুষ্ট ব্যবহারকারী</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->
</div>
