<div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        {{-- --- সার্চ ফিল্ড এবং বাটনকে পাশাপাশি আনার জন্য নতুন কোড --- --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            {{-- সার্চ ফিল্ড (বাম পাশে) --}}
            <div class="w-full"> {{-- অথবা আপনার পছন্দমতো প্রস্থ, যেমন: col-md-4 --}}
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    class="form-control"
                    placeholder="লিস্টিং খুঁজুন..."
                >
            </div>

            {{-- নতুন লিস্টিং তৈরির বাটন (ডান পাশে) --}}
            <div>
                <a href="{{ route('listings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>নতুন লিস্টিং
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-nowrap">
                <tr>
                    <th>ছবি</th>
                    <th>শিরোনাম</th>
                    <th>ধরন</th>
                    <th>স্ট্যাটাস</th>
                    <th>মাসিক ভাড়া</th>
                    <th class="text-end">অ্যাকশন</th>
                </tr>
                </thead>
                <tbody>
                @forelse($properties as $property)
                    <tr wire:key="{{ $property->id }}" class="text-nowrap">
                        {{-- ছবি --}}
                        <td style="width: 80px;">
                            <a href="{{ route('listings.edit', $property) }}" class="d-inline-block">
                                <div style="width: 70px; height: 70px; overflow: hidden; border-radius: 8px; border: 1px solid #dee2e6;">
                                    @if($property->hasMedia('thumbnail'))
                                        <img
                                            src="{{ $property->getFirstMediaUrl('thumbnail') }}"
                                            alt="{{ $property->title }}"
                                            class="w-100 h-100 img-thumbnail rounded shadow-sm"
                                            style="object-fit: cover;">
                                    @else
                                        <img
                                            src="https://ui-avatars.com/api/?name={{ urlencode(substr($property->title, 0, 2)) }}&size=60&background=e9ecef&color=6c757d"
                                            alt="No image"
                                            class="w-100 h-100 img-thumbnail rounded shadow-sm"
                                            style="object-fit: cover;">
                                    @endif
                                </div>
                            </a>
                        </td>

                        {{-- শিরোনাম --}}
                        <td>
                            <div class="fw-semibold mb-1">
                                <a href="{{ route('listings.edit', $property) }}" class="text-decoration-none fw-bold">
                                    {{ Str::limit($property->title, 40) }}
                                </a>
                            </div>
                            <small class="text-muted">
                                {{ $property->property_code }}
                            </small>
                        </td>

                        {{-- ধরন --}}
                        <td>{{ $property->propertyType->name_bn }}</td>

                        {{-- স্ট্যাটাস --}}
                        <td>
                            @if($property->status === 'pending')
                                <span class="badge bg-warning text-dark text-capitalize px-3 py-2 shadow-sm">
                                {{ $property->status }}
                            </span>
                            @else
                                <select
                                    class="form-select form-select-sm w-auto shadow-sm
                                    @if($property->status === 'active') bg-success text-white
                                    @elseif($property->status === 'pending') bg-warning text-dark
                                    @elseif($property->status === 'rented') bg-info text-white
                                    @else bg-secondary text-white @endif"
                                    wire:change="updateStatus({{ $property->id }}, $event.target.value)"
                                >
                                    @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" {{ $property->status == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </td>

                        {{-- মাসিক ভাড়া --}}
                        <td class="fw-semibold">৳{{ number_format($property->rent_price) }}</td>

                        {{-- অ্যাকশন --}}
                        <td class="text-end text-nowrap">
                            @can('update', $property)
                                <a href="{{ route('listings.edit', $property) }}"
                                   class="btn btn-sm btn-outline-secondary shadow-sm">
                                    <i class="fas fa-edit me-1"></i> এডিট
                                </a>
                            @endcan

                            @can('delete', $property)
                                <form action="{{ route('listings.destroy', $property) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('আপনি কি এই লিস্টিংটি মুছে ফেলতে নিশ্চিত?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                                        <i class="fas fa-trash me-1"></i> ডিলিট
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            আপনি এখনো কোনো লিস্টিং যোগ করেননি।
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>




{{--        <div class="table-responsive">--}}
{{--        <table class="table table-hover">--}}
{{--            <thead class="table-light">--}}
{{--            <tr class="whitespace-nowrap">--}}
{{--                <th>ছবি</th>--}}
{{--                <th>শিরোনাম</th>--}}
{{--                <th>ধরন</th>--}}
{{--                <th>স্ট্যাটাস</th>--}}
{{--                <th>মাসিক ভাড়া</th>--}}
{{--                <th class="text-end">অ্যাকশন</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @forelse($properties as $property)--}}
{{--                <tr wire:key="{{ $property->id }}" class="whitespace-nowrap">--}}
{{--                    --}}{{-- নতুন থাম্বনেইল সেল --}}
{{--                    <td>--}}
{{--                        <a href="{{ route('listings.edit', $property) }}">--}}
{{--                            --}}{{-- getFirstMediaUrl('thumbnail', 'thumb') ব্যবহার করা হচ্ছে --}}
{{--                            @if($property->hasMedia('thumbnail'))--}}
{{--                                <img--}}
{{--                                    src="{{ $property->getFirstMediaUrl('thumbnail') }}"--}}
{{--                                    alt="{{ $property->title }}" class="img-thumbnail" style="width: 70px; height: 70px; object-fit: cover;">--}}
{{--                            @else--}}
{{--                                --}}{{-- যদি কোনো ছবি না থাকে, তাহলে একটি প্লেসহোল্ডার দেখানো হবে --}}
{{--                                <img src="https://ui-avatars.com/api/?name={{ urlencode(substr($property->title, 0, 2)) }}&size=60&background=e9ecef&color=6c757d" alt="No image" class="img-thumbnail">--}}
{{--                            @endif--}}
{{--                        </a>--}}
{{--                    </td>--}}

{{--                    <td>--}}
{{--                        <div class="fw-bold">--}}
{{--                            <a href="{{ route('listings.edit', $property) }}">{{ Str::limit($property->title, 40) }}</a>--}}
{{--                        </div>--}}
{{--                        <small class="text-muted">--}}
{{--                            <a href="{{ route('listings.edit', $property) }}">{{ $property->property_code }}</a>--}}
{{--                        </small>--}}
{{--                    </td>--}}
{{--                    <td>{{ $property->propertyType->name_bn }}</td>--}}
{{--                    <td>--}}
{{--                        @if($property->status === 'pending')--}}
{{--                            <span class="badge bg-warning text-capitalize">{{ $property->status }}</span>--}}
{{--                        @else--}}
{{--                            --}}{{-- স্ট্যাটাস পরিবর্তনের জন্য ড্রপডাউন --}}
{{--                            <select--}}
{{--                                class="form-select form-select-sm w-auto--}}
{{--                                    @if($property->status === 'active') bg-success text-white--}}
{{--                                    @elseif($property->status === 'pending') bg-warning text-white--}}
{{--                                    @elseif($property->status === 'rented') bg-info text-white--}}
{{--                                    @else bg-secondary text-white @endif"--}}
{{--                                wire:change="updateStatus({{ $property->id }}, $event.target.value)"--}}
{{--                            >--}}
{{--                                @foreach($statusOptions as $status)--}}
{{--                                    <option value="{{ $status }}" {{ $property->status == $status ? 'selected' : '' }}>--}}
{{--                                        {{ ucfirst($status) }}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td>৳{{ number_format($property->rent_price) }}</td>--}}
{{--                    <td class="text-end">--}}
{{--                        --}}{{-- 'update' পারমিশন চেক করা হচ্ছে --}}
{{--                        @can('update', $property)--}}
{{--                            <a href="{{ route('listings.edit', $property) }}" class="btn btn-sm btn-outline-secondary">--}}
{{--                                <i class="fas fa-edit me-1"></i> এডিট--}}
{{--                            </a>--}}
{{--                        @endcan--}}

{{--                        --}}{{-- 'delete' পারমিশন চেক করা হচ্ছে --}}
{{--                        @can('delete', $property)--}}
{{--                            <form action="{{ route('listings.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি এই লিস্টিংটি মুছে ফেলতে নিশ্চিত?')">--}}
{{--                                @csrf--}}
{{--                                @method('DELETE')--}}
{{--                                <button type="submit" class="btn btn-sm btn-outline-danger">--}}
{{--                                    <i class="fas fa-trash me-1"></i> ডিলিট--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        @endcan--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @empty--}}
{{--                <tr>--}}
{{--                    <td colspan="5" class="text-center py-4">আপনি এখনো কোনো লিস্টিং যোগ করেননি।</td>--}}
{{--                </tr>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}

    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>
