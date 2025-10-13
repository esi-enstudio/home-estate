<picture>
    {{-- ডার্ক মোডের জন্য সোর্স --}}
    <source srcset="{{ asset('assets/img/logo-white.svg') }}" media="(prefers-color-scheme: dark)">

    {{-- ডিফল্ট বা লাইট মোডের জন্য ইমেজ --}}
    <img src="{{ asset('assets/img/logo.svg') }}" alt="{{ config('app.name') }} Logo" style="height: 2rem;">
</picture>
