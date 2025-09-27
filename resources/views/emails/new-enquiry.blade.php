<x-mail::message>
    # New Enquiry Received

    Hello {{ $enquiry->property->user->name }},

    You have received a new enquiry for your property listing: **"{{ $enquiry->property->title }}"**.

    Here are the details:

    - **Name:** {{ $enquiry->name }}
    - **Email:** {{ $enquiry->email }}
    - **Phone:** {{ $enquiry->phone }}

    ---

    **Message:**

    {{ $enquiry->message }}

    ---

    You can view the property listing by clicking the button below.

    <x-mail::button :url="route('properties.show', $enquiry->property)">
        View Property
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
