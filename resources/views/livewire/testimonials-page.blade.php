<div>
    @if($testimonials->isNotEmpty())
        <!-- start row -->
        <div class="row row-gap-4">
            @foreach($testimonials as $testimonial)
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-0 h-100"> {{-- h-100 ক্লাস কার্ডগুলোর উচ্চতা সমান রাখবে --}}
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-warning">
                                        <i class="material-icons-outlined">{{ $i <= $testimonial->rating ? 'star' : 'star_border' }}</i>
                                    </span>
                                @endfor
                            </div>
                            <p>"{{ Str::limit($testimonial->body, 150) }}"</p>
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);">
                                    <img src="{{ \Storage::url($testimonial->user->avatar_url) ?? 'https://placehold.co/100' }}" alt="{{ $testimonial->user->name }}" class="avatar avatar-lg rounded-circle me-2">
                                </a>
                                <div>
                                    <a href="javascript:void(0);" class="fw-semibold mb-1">{{ $testimonial->user->name }}</a>
                                    <p class="mb-0">{{ $testimonial->user->designation }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
            @endforeach
        </div>
        <!-- end row -->

        <!-- Load More বাটন -->
        @if($hasMorePages)
            <div class="mt-4 d-flex justify-content-center">
                <button wire:click="loadMore" wire:loading.attr="disabled" class="btn btn-dark d-inline-flex align-items-center">
                    {{-- লোডিং স্টেট --}}
                    <span wire:loading.remove wire:target="loadMore">
                <i class="material-icons-outlined me-1">autorenew</i>আরও লোড করুন
            </span>
                    <span wire:loading wire:target="loadMore">
                লোড হচ্ছে...
            </span>
                </button>
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <h5>দুঃখিত, এই মুহূর্তে কোনো প্রশংসাপত্র যোগ করা হয়নি।</h5>
        </div>
    @endif
</div>
