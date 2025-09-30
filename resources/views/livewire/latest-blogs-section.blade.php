<section class="blogs-section">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title-2" data-aos="fade-up" data-aos-duration="1000">
            <div class="d-flex align-items-center justify-content-center">
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
                <h2>আমাদের সাম্প্রতিক <span>ব্লগ</span></h2>
                <span class="title-square bg-primary"></span><span class="title-square bg-secondary"></span>
            </div>
            <p>প্রপার্টি ক্রয়-বিক্রয় এবং ভাড়ার সর্বশেষ খবর ও টিপস জানতে চোখ রাখুন আমাদের ব্লগে।</p>
        </div>
        <!-- Section Title End -->

        @if($latestPosts->isNotEmpty())
            <div class="row justify-content-center">
                @foreach($latestPosts as $post)
                    <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="{{ 1000 + ($loop->index * 500) }}">
                        <div class="blog-item-two">
                            <div class="blog-content">
                                <div class="blog-img">
                                    <a href="{{ route('blog.show', $post) }}">
                                        <img src="{{ $post->getFirstMediaUrl('featured_post_image') ?: 'https://placehold.co/400x300' }}" class="img-fluid" alt="{{ $post->title }}">
                                    </a>
                                </div>
                                <div class="position-absolute top-0 start-0 p-3 z-1">
                                    <div class="blog-date">
                                        <h6 class="mb-0">{{ $post->published_at->format('d') }}</h6>
                                        <span>{{ $post->published_at->format('M') }}</span>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 start-0 end-0 p-3 text-center z-1">
                                    <span class="badge bg-danger mb-2">{{ $post->category->name }}</span>
                                    <h5 class="mb-0"><a href="{{ route('blog.show', $post) }}">{{ Str::limit($post->title, 50) }}</a></h5>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                @endforeach
            </div>
        @endif

    </div>
</section>
