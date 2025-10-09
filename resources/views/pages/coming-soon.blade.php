@extends('layouts.app')

@section('title', 'Coming Soon | '. config('app.name'))

@section('content')
    <div class="page-wrapper">
        <!-- Start Content -->
        <div class="container-fuild">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 coming-soon-cover">
                <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap">
                    <div class="col-lg-10 mx-auto">
                        <div class="coming-soon-item">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div>
                                    <h1>{{ $settings->coming_soon_title ?? 'শীঘ্রই আসছি' }}</h1>
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mx-auto px-4">
                                            {{-- === START: ডাইনামিক কাউন্টডাউন টাইমার === --}}
                                            @if($settings->launch_date)
                                                <div id="countdown-timer" data-launch-date="{{ $settings->launch_date }}">
                                                    <ul class="d-flex list-unstyled align-items-center justify-content-center mb-3">
                                                        <li class="me-sm-3 me-2"><div class="timer-cover"><h6 class="days fw-bold mb-0">00</h6></div><p class="text-center mb-0">দিন</p></li>
                                                        <li class="me-sm-3 mb-2 me-2">:</li>
                                                        <li class="me-sm-3 me-2"><div class="timer-cover"><h6 class="hours mb-0">00</h6></div><p class="text-center mb-0">ঘন্টা</p></li>
                                                        <li class="me-sm-3 mb-2 me-2">:</li>
                                                        <li class="me-sm-3 me-2"><div class="timer-cover"><h6 class="minutes mb-0">00</h6></div><p class="text-center mb-0">মিনিট</p></li>
                                                        <li class="me-sm-3 mb-2 me-2">:</li>
                                                        <li><div class="timer-cover"><h6 class="seconds mb-0">00</h6></div><p class="text-center mb-0">সেকেন্ড</p></li>
                                                    </ul>
                                                </div>
                                            @endif
                                            {{-- === END === --}}

                                            <div class="mb-3">
                                                <p class="d-flex text-center justify-content-center">{{ $settings->coming_soon_subtitle ?? 'আমাদের ওয়েবসাইটটি বর্তমানে নির্মাণাধীন। খুব শীঘ্রই আমরা আসছি। আমাদের সাথেই থাকুন!' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- === START: কাউন্টডাউন টাইমারের জন্য JavaScript === --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timerElement = document.getElementById('countdown-timer');
            if (!timerElement) return;

            const launchDate = new Date(timerElement.dataset.launchDate).getTime();

            const countdown = setInterval(function() {
                const now = new Date().getTime();
                const distance = launchDate - now;

                if (distance < 0) {
                    clearInterval(countdown);
                    // আপনি চাইলে টাইমার শেষ হলে কোনো বার্তা দেখাতে পারেন
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.querySelector('.days').innerText = days < 10 ? '0' + days : days;
                timerElement.querySelector('.hours').innerText = hours < 10 ? '0' + hours : hours;
                timerElement.querySelector('.minutes').innerText = minutes < 10 ? '0' + minutes : minutes;
                timerElement.querySelector('.seconds').innerText = seconds < 10 ? '0' + seconds : seconds;

            }, 1000);
        });
    </script>
    {{-- === END === --}}
@endpush

{{--এই পরিবর্তনের পর, আপনার "Coming Soon" পেজটি এখন সম্পূর্ণরূপে অ্যাডমিন প্যানেল থেকে পরিচালনাযোগ্য এবং একটি কার্যকরী কাউন্টডাউন টাইমারসহ প্রদর্শিত হবে।--}}
