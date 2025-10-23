<div class="property-listing-item">
    <div class="buy-grid-img">
        <a href="{{ route('properties.show', $property) }}">
            <img class="img-fluid rounded" src="{{ $property->getFirstMediaUrl('thumbnail', 'preview') ?: 'https://placehold.co/400x250' }}" alt="{{ $property->title }}">
        </a>
        <div class="d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 p-3">
            <div class="d-flex align-items-center gap-2">
                @if($property->is_trending)
                    <div class="badge badge-sm bg-danger d-flex align-items-center"><i class="material-icons-outlined">offline_bolt</i>ট্রেন্ডিং</div>
                @endif
                @if($property->is_featured)
                    <div class="badge badge-sm bg-orange d-flex align-items-center"><i class="material-icons-outlined">loyalty</i>ফিচার্ড</div>
                @endif
            </div>

            <livewire:favorite-button :property="$property" :key="'fav-btn-' . $property->id" />
        </div>
        <div class="d-flex align-items-center justify-content-between position-absolute bottom-0 end-0 start-0 p-3">
            <span class="badge bg-light text-dark">
                @if($property->purpose === 'rent') ভাড়ার জন্য @else বিক্রির জন্য @endif
            </span>
            <div class="user-avatar avatar avatar-md">
                <img src="{{ \Storage::url($property->user->avatar_url) ?? 'https://placehold.co/100' }}" alt="{{ $property->user->name }}" class="rounded-circle">
            </div>
        </div>
    </div>
    <div class="buy-grid-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="title"><a href="{{ route('properties.show', $property) }}">{{ Str::limit($property->title, 25) }}</a></h6>
                <p class="d-flex align-items-center fs-14 mb-0"><i class="material-icons-outlined me-1">location_on</i> {{ $property->address_area }}, {{ $property->district->name }}</p>
            </div>
            <span class="badge bg-secondary">{{ $property->propertyType->name_bn }}</span>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
            <div class="d-flex align-items-center justify-content-center">
                @for($i = 1; $i <= 5; $i++)
                    <i class="material-icons-outlined text-warning fs-14">{{ $i <= round($property->average_rating) ? 'star' : 'star_border' }}</i>
                @endfor
                <span class="ms-1 fs-14">{{ number_format($property->average_rating, 1) }}</span>
            </div>
            <div class="d-flex align-items-center">
                <h6 class="text-primary mb-0 ms-1">৳{{ number_format($property->rent_price) }} <span class="fs-14 fw-normal text-dark">/মাসিক</span></h6>
            </div>
        </div>
        <ul class="d-flex buy-grid-details justify-content-between align-items-center flex-wrap gap-1">
            <li class="d-flex align-items-center gap-1"><i class="material-icons-outlined bg-light text-dark">bed</i> {{ $property->bedrooms }} বেডরুম</li>
            <li class="d-flex align-items-center gap-1"><i class="material-icons-outlined bg-light text-dark">bathtub</i> {{ $property->bathrooms }} বাথরুম</li>
            <li class="d-flex align-items-center gap-1"><i class="material-icons-outlined bg-light text-dark">straighten</i> {{ $property->size_sqft }} স্কয়ার ফিট</li>
        </ul>
    </div>
</div>
