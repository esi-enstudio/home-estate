<footer class="footer-two">
    <div class="container">

        <div class="join-sec">
            <div>
                <h2>{{ $footerSettings->join_title ?? 'আপনার কাজের অভিজ্ঞতাকে নতুন করে সংজ্ঞায়িত করুন!' }}</h2>
                <p>{{ $footerSettings->join_subtitle ?? 'আমাদের সাথে যুক্ত হন, সহযোগিতা বাড়ান এবং সাফল্য অর্জন করুন।' }}</p>
            </div>
            <a href="{{ $footerSettings->join_button_link ?? '#' }}" class="btn btn-primary btn-lg">{{ $footerSettings->join_button_text ?? 'আপনার প্রপার্টি যোগ করুন' }}</a>
        </div>

        <!-- Footer Top -->
        <div class="footer-top">
            <div class="row gy-4">
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h5 class="footer-title">কোম্পানি</h5>
                        {{-- KeyValue থেকে আসা ডেটা empty() দিয়ে চেক করা ভালো --}}
                        @if(!empty($footerSettings->company_menu))
                            <ul class="footer-menu">
                                {{-- === START: KeyValue-এর জন্য সঠিক @foreach লুপ === --}}
                                @foreach($footerSettings->company_menu as $label => $url)
                                    <li><a href="{{ $url }}">{{ $label }}</a></li>
                                @endforeach
                                {{-- === END === --}}
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h5 class="footer-title">গন্তব্য</h5>
                        @if(!empty($footerSettings->destinations_menu))
                            <ul class="footer-menu">
                                {{-- === START: KeyValue-এর জন্য সঠিক @foreach লুপ === --}}
                                @foreach($footerSettings->destinations_menu as $label => $url)
                                    <li><a href="{{ $url }}">{{ $label }}</a></li>
                                @endforeach
                                {{-- === END === --}}
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-contacts">
                        <h5 class="footer-title">আমাদের খুঁজুন</h5>
                        <div class="contact-info">
                            <h6>ঠিকানা</h6>
                            <p>{{ $footerSettings->location ?? 'N/A' }}</p>
                        </div>
                        <div class="contact-info">
                            <h6>ফোন</h6>
                            <p>{{ $footerSettings->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="contact-info">
                            <h6>ইমেইল</h6>
                            <p><a href="mailto:{{ $footerSettings->email }}">{{ $footerSettings->email ?? 'N/A' }}</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    {{-- নিউজলেটার সাবস্ক্রাইব Livewire কম্পোনেন্ট এখানে রেন্ডার হবে --}}
                    <livewire:newsletter-subscribe :title="$footerSettings->newsletter_title" :subtitle="$footerSettings->newsletter_subtitle"/>

                    <div class="social-icon">
                        @if($footerSettings->facebook_link)<a href="{{ $footerSettings->facebook_link }}" target="_blank"><i class="fa-brands fa-facebook"></i></a>@endif
                        @if($footerSettings->twitter_link)<a href="{{ $footerSettings->twitter_link }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>@endif
                        @if($footerSettings->instagram_link)<a href="{{ $footerSettings->instagram_link }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>@endif
                        @if($footerSettings->linkedin_link)<a href="{{ $footerSettings->linkedin_link }}" target="_blank"><i class="fa-brands fa-linkedin"></i></a>@endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /Footer Top -->

    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <p class="copy-right">কপিরাইট &copy; {{ date('Y') }}. সর্বস্বত্ব সংরক্ষিত, {{ config('app.name') }}</p>

                @if(isset($footerPages) && $footerPages->isNotEmpty())
                    <div class="policy-link">
                        @foreach($footerPages as $page)
                            <a href="{{ route('page.show', $page) }}">{{ $page->title }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /Footer Bottom -->
</footer>
