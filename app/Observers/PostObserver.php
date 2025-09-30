<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle events after all transactions are committed.
     * @var bool
     */
    public bool $afterCommit = true;

    // যখনই কোনো পোস্ট সেভ (তৈরি/আপডেট) বা ডিলিট হবে
    public function saved(Post $post): void
    {
        $this->clearCache();
    }

    public function deleted(Post $post): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::forget('latest_blog_posts');
    }
}
