<x-mail::message>
    # ওয়েবসাইট থেকে নতুন বার্তা!

    অ্যাডমিন,

    আপনি ওয়েবসাইট কন্টাক্ট ফর্ম থেকে একটি নতুন বার্তা পেয়েছেন।

    ### প্রেরকের বিবরণ:
    - **নাম:** {{ $message->name }}
    - **ইমেইল:** {{ $message->email }}
    - **ফোন:** {{ $message->phone }}

    ---

    ### বার্তার বিষয়:
    **{{ $message->subject }}**

    ### বার্তা:
    > {{ $message->message }}

    আপনি নিচের বাটনে ক্লিক করে অ্যাডমিন প্যানেল থেকে বার্তাটি দেখতে পারেন।

    @php
        $manageUrl = \App\Filament\Resources\MessageResource::getUrl('view', ['record' => $message]);
    @endphp

    <x-mail::button :url="$manageUrl">
        বার্তাটি দেখুন
    </x-mail::button>

    ধন্যবাদ,<br>
    {{ config('app.name') }}
</x-mail::message>
