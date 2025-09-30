<x-mail::message>
    # আপনার প্রপার্টির জন্য নতুন রিভিউ!

    হ্যালো {{ $review->property->user->name }},

    আপনার প্রপার্টি **"{{ $review->property->title }}"**-এর জন্য একটি নতুন রিভিউ জমা দেওয়া হয়েছে। রিভিউটি বর্তমানে অনুমোদনের জন্য অপেক্ষারত (Pending) আছে।

    ### রিভিউর বিবরণ:
    *   **রিভিউ প্রদানকারী:** {{ $review->user->name }}
    *   **রেটিং:** {{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }} / 5)
    *   **শিরোনাম:** {{ $review->title }}

    > {{ $review->body }}

    আপনি নিচের বাটনে ক্লিক করে আপনার অ্যাডমিন প্যানেল থেকে রিভিউটি পরিচালনা (Approve/Reject) করতে পারেন।

    @php
        // নোটিফিকেশনের মতোই ডাইনামিক লিংক তৈরি করা হচ্ছে
        $manageUrl = \App\Filament\Resources\PropertyResource::getUrl('edit', ['record' => $review->property]) . '?activeRelationManager=1';
    @endphp

    <x-mail::button :url="$manageUrl">
        রিভিউ পরিচালনা করুন
    </x-mail::button>

    ধন্যবাদ,<br>
    {{ config('app.name') }}
</x-mail::message>
