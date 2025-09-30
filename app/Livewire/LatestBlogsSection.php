<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class LatestBlogsSection extends Component
{
    public $latestPosts = [];

    public function mount(): void
    {
        // ১ ঘণ্টার জন্য ফলাফল ক্যাশ করা হবে
        $this->latestPosts = Cache::remember('latest_blog_posts', now()->addHour(), function () {
            // শুধুমাত্র 'published' স্ট্যাটাসের পোস্টগুলো আনা হচ্ছে
            return Post::where('status', 'published')
                ->with('media', 'category') // N+1 সমস্যা এড়ানোর জন্য রিলেশনশিপ লোড করা হচ্ছে
                ->latest('published_at') // সর্বশেষ প্রকাশিত পোস্টগুলো আগে আসবে
                ->take(3) // ডিজাইনে ৩টি পোস্ট আছে
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.latest-blogs-section');
    }
}
