<x-mail::message>
    # নতুন প্রপার্টি অনুমোদনের জন্য অপেক্ষারত

    হ্যালো অ্যাডমিন,

    **{{ $property->user->name }}** আপনার প্ল্যাটফর্মে একটি নতুন প্রপার্টি লিস্টিং জমা দিয়েছেন, যা পর্যালোচনার জন্য অপেক্ষারত আছে।

    ### প্রপার্টির বিবরণ:
    - **শিরোনাম:** {{ $property->title }}
    - **মালিক:** {{ $property->user->name }}
    - **স্ট্যাটাস:** {{ $property->status }}

    আপনি নিচের বাটনে ক্লিক করে অ্যাডমিন প্যানেল থেকে প্রপার্টিটি পর্যালোচনা এবং অনুমোদন করতে পারেন।

    @php
        $manageUrl = \App\Filament\Resources\PropertyResource::getUrl('edit', ['record' => $property]);
    @endphp

    <x-mail::button :url="$manageUrl">
        প্রপার্টি পর্যালোচনা করুন
    </x-mail::button>

    ধন্যবাদ,
    {{ config('app.name') }}
</x-mail::message>
