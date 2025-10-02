{{-- এই <a> ট্যাগটিই পুরো ব্র্যান্ডিংকে ক্লিকেবল করে তুলবে --}}
<a href="{{ route('home') }}" class="flex items-center">
    {{-- যদি আপনার লোগো একটি ছবি হয় --}}
    {{--    <img src="{{ asset('path/to/your/logo.svg') }}" alt="{{ config('app.name') }}" class="h-10">--}}

    {{-- অথবা, যদি আপনি শুধু টেক্সট দেখাতে চান --}}
    <span class="text-xl font-bold text-gray-800 dark:text-white">
        {{ config('app.name') }}
    </span>
</a>
