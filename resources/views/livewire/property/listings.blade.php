<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>আমার লিস্টিং</h3>
        <a href="{{ route('my-listings.create') }}" class="btn btn-primary">নতুন লিস্টিং যোগ করুন</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ছবি</th>
                <th>শিরোনাম</th>
                <th>স্ট্যাটাস</th>
                <th>মূল্য</th>
                <th>অ্যাকশন</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($properties as $property)
                <tr>
                    <td><img src="{{ $property->getFirstMediaUrl('thumbnail', 'thumb') }}" alt="{{ $property->title }}" width="100"></td>
                    <td>{{ $property->title }}</td>
                    <td><span class="badge bg-info">{{ $property->status }}</span></td>
                    <td>{{ number_format($property->rent_price) }}</td>
                    <td>
                        <a href="{{ route('my-listings.edit', $property) }}" class="btn btn-sm btn-secondary">এডিট</a>
                        <button class="btn btn-sm btn-danger"
                                wire:click="delete({{ $property->id }})"
                                wire:confirm="আপনি কি নিশ্চিতভাবে এই লিস্টিংটি মুছে ফেলতে চান?">
                            ডিলিট
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">এখনো কোনো লিস্টিং যোগ করা হয়নি।</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>
