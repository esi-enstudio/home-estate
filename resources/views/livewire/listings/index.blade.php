<div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        {{-- --- সার্চ ফিল্ড এবং বাটনকে পাশাপাশি আনার জন্য নতুন কোড --- --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            {{-- সার্চ ফিল্ড (বাম পাশে) --}}
            <div class="w-25"> {{-- অথবা আপনার পছন্দমতো প্রস্থ, যেমন: col-md-4 --}}
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
                    <i class="fas fa-plus me-2"></i>নতুন লিস্টিং যোগ করুন
                </a>
            </div>
        </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
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
                <tr wire:key="{{ $property->id }}">
                    {{-- নতুন থাম্বনেইল সেল --}}
                    <td>
                        <a href="{{ route('listings.edit', $property) }}">
                            {{-- getFirstMediaUrl('thumbnail', 'thumb') ব্যবহার করা হচ্ছে --}}
                            @if($property->hasMedia('thumbnail'))
                                <img
                                    src="{{ $property->getFirstMediaUrl('thumbnail') }}"
                                    alt="{{ $property->title }}" class="img-thumbnail" style="width: 70px; height: 70px; object-fit: cover;">
                            @else
                                {{-- যদি কোনো ছবি না থাকে, তাহলে একটি প্লেসহোল্ডার দেখানো হবে --}}
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(substr($property->title, 0, 2)) }}&size=60&background=e9ecef&color=6c757d" alt="No image" class="img-thumbnail">
                            @endif
                        </a>
                    </td>

                    <td>
                        <div class="fw-bold">
                            <a href="{{ route('listings.edit', $property) }}">{{ Str::limit($property->title, 40) }}</a>
                        </div>
                        <small class="text-muted">
                            <a href="{{ route('listings.edit', $property) }}">{{ $property->property_code }}</a>
                        </small>
                    </td>
                    <td>{{ $property->propertyType->name_bn }}</td>
                    <td>
                        @if($property->status === 'pending')
                            <span class="badge bg-warning text-capitalize">{{ $property->status }}</span>
                        @else
                            {{-- স্ট্যাটাস পরিবর্তনের জন্য ড্রপডাউন --}}
                            <select
                                class="form-select form-select-sm w-auto
                                    @if($property->status === 'active') bg-success text-white
                                    @elseif($property->status === 'pending') bg-warning text-white
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
                    <td>৳{{ number_format($property->rent_price) }}</td>
                    <td class="text-end">
                        {{-- 'update' পারমিশন চেক করা হচ্ছে --}}
                        @can('update', $property)
                            <a href="{{ route('listings.edit', $property) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i> এডিট
                            </a>
                        @endcan

                        {{-- 'delete' পারমিশন চেক করা হচ্ছে --}}
                        @can('delete', $property)
                            <form action="{{ route('listings.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি এই লিস্টিংটি মুছে ফেলতে নিশ্চিত?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash me-1"></i> ডিলিট
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">আপনি এখনো কোনো লিস্টিং যোগ করেননি।</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>
