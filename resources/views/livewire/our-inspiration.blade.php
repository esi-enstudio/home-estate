<div>
    <!-- start row -->
    <div class="row row-gap-4">
        @forelse($members as $member)
            <div class="col-md-6 col-lg-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="javascript:void(0);"><img src="{{ \Storage::url($member->avatar_url) ?? 'https://placehold.co/100' }}" alt="{{ $member->name }}" class="avatar avatar-xl rounded-circle mb-4"></a>
                            <a href="javascript:void(0);" class="fw-semibold d-block">{{ $member->name }}</a>
                            <p class="mb-4">{{ $member->designation }}</p>
                            @if(!empty($member->social_links))
                                <div class="d-flex align-items-center justify-content-center">
                                    @foreach($member->social_links as $platform => $url)
                                        @if(!empty($url))
                                            <a href="{{ $url }}" target="_blank" class="btn btn-light rounded-2 p-2 d-inline-flex align-items-center justify-content-end border-0 me-2">
                                                <i class="fa-brands fa-{{ strtolower($platform) }}"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5"><h5>এই মুহূর্তে কোনো টিম মেম্বারের তথ্য যোগ করা হয়নি।</h5></div>
        @endforelse
    </div>
    <!-- end row -->

    @if($hasMorePages)
        <div class="mt-4 d-flex justify-content-center">
            <button wire:click="loadMore" wire:loading.attr="disabled" class="btn btn-dark d-inline-flex align-items-center">
                <span wire:loading.remove>আরও দেখুন</span>
                <span wire:loading>লোড হচ্ছে...</span>
            </button>
        </div>
    @endif
</div>
