<div class="content">
    <div class="container">
        <div class="row row-gap-4">
            {{-- ===== মূল ব্লগ তালিকা ===== --}}
            <div class="col-md-12 col-lg-8">
                @forelse($posts as $post)
                    <div class="blog-item-01 mb-4">
                        <div class="blog-img">
                            <a href="{{ route('blog.show', $post) }}">
                                <img src="{{ $post->getFirstMediaUrl('featured_post_image') ?: 'https://placehold.co/800x400' }}" alt="{{ $post->title }}" class="img-fluid">
                            </a>
                        </div>
                        <div class="blog-content">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                                <a href="#" wire:click.prevent="filterByCategory({{ $post->category->id }})" class="badge badge-sm bg-secondary fw-semibold">{{ $post->category->name }}</a>
                                <div class="d-flex align-items-center flex-wrap gap-3 author-details">
                                    <div class="d-flex align-items-center me-3">
                                        <a href="#"><img src="{{ $post->user->avatar_url ?? 'https://placehold.co/100' }}" alt="{{ $post->user->name }}" class="avatar avatar-sm rounded-circle me-2"></a>
                                        <a href="#">{{ $post->user->name }}</a>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="d-inline-flex align-items-center"><i class="material-icons-outlined me-1">event</i>{{ $post->published_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h5>
                                <p class="mb-0">{{ Str::limit(strip_tags($post->body), 150) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <h4>দুঃখিত, কোনো পোস্ট পাওয়া যায়নি।</h4>
                        <p>আপনার সার্চ বা ফিল্টারের সাথে মিলে এমন কোনো পোস্ট নেই।</p>
                    </div>
                @endforelse

                {{-- পেজিনেশন লিঙ্কস --}}
                @if($hasMorePages)
                    <div class="d-flex align-items-center justify-content-center">
                        <button wire:click="loadMore" wire:loading.attr="disabled" class="btn btn-dark d-inline-flex align-items-center load-more-btn">
                            {{-- লোডিং স্টেট --}}
                            <span wire:loading.remove wire:target="loadMore">
                                <i class="material-icons-outlined me-1">autorenew</i>আরও লোড করুন
                            </span>

                            <span wire:loading wire:target="loadMore">
                                লোড হচ্ছে...
                            </span>
                        </button>
                    </div>
                @endif
            </div>

            {{-- ===== সাইডবার ===== --}}
            <div class="col-lg-4 theiaStickySidebar">
                <div class="card">
                    <div class="card-header"><h4 class="mb-0">অনুসন্ধান</h4></div>
                    <div class="card-body">
                        <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="এখানে খুঁজুন...">
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4 class="mb-0">ক্যাটাগরি সমূহ</h4></div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <a href="#" wire:click.prevent="filterByCategory(null)" class="link-body @if(!$selectedCategory) fw-bold text-primary @endif">সকল পোস্ট</a>
                        </div>
                        @foreach($categories as $category)
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 @if(!$loop->last) mb-3 @endif">
                                <a href="#" wire:click.prevent="filterByCategory({{ $category->id }})" class="link-body @if($selectedCategory === $category->id) fw-bold text-primary @endif">{{ $category->name }}</a>
                                <p class="mb-0">{{ $category->posts_count }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-0">
                    <div class="card-header"><h4 class="mb-0">সাম্প্রতিক পোস্ট</h4></div>
                    <div class="card-body">
                        @foreach($topArticles as $article)
                            <div class="blog-item-02 @if(!$loop->last) mb-3 @endif">
                                <div class="blog-img-img">
                                    <a href="{{ route('blog.show', $article) }}"><img src="{{ $article->getFirstMediaUrl('featured_post_image') ?: 'https://placehold.co/100x80' }}" alt="{{ $article->title }}" class="img-fluid"></a>
                                </div>
                                <div class="blog-content-02">
                                    <h5><a href="{{ route('blog.show', $article) }}">{{ Str::limit($article->title, 30) }}</a></h5>
                                    <p>{{ $article->published_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
