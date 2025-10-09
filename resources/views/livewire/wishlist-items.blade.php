<div>
    <ul class="nav nav-pills whishlist-item gap-2" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ is_null($selectedPropertyTypeId) ? 'active' : '' }}" href="#" wire:click.prevent="filterByType(null)">
                <i class="material-icons-outlined me-2">shopping_basket</i> সকল প্রপার্টি
            </a>
        </li>
        @foreach($propertyTypes as $type)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $selectedPropertyTypeId === $type->id ? 'active' : '' }}" href="#" wire:click.prevent="filterByType({{ $type->id }})">
                    <i class="material-icons-outlined me-2">{{ $type->icon_path ?? 'king_bed' }}</i>{{ $type->name_bn }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" wire:loading.class="opacity-50">
        <div class="tab-pane fade active show">
            <div class="row mb-4">
                @forelse($properties as $property)
                    <div class="col-xl-4 col-lg-6 col-md-6 d-flex" wire:key="property-wrapper-{{ $property->id }}">
                        {{-- পুনঃব্যবহারযোগ্য প্রপার্টি কার্ড --}}
                        @include('partials._property-card', ['property' => $property])
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5>আপনার পছন্দের তালিকায় কোনো প্রপার্টি নেই।</h5>
                        <p>অনুগ্রহ করে কিছু প্রপার্টি ফেভারিট করুন।</p>
                    </div>
                @endforelse
            </div>

            {{-- পেজিনেশন লিঙ্কস --}}
            <div>
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</div>
