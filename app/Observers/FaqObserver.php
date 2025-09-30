<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Cache;

class FaqObserver
{
    /**
     * Handle events after all transactions are committed.
     * এটি নিশ্চিত করে যে ডাটাবেজ ট্রানজেকশন সফলভাবে শেষ হওয়ার পরেই এই কোডটি চলবে,
     * যা ডেটা ইনকনসিস্টেন্সি বা race condition প্রতিরোধ করে।
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the Faq "created" event.
     */
    public function created(Faq $faq): void
    {
        $this->clearHomepageFaqCache();
    }

    /**
     * Handle the Faq "updated" event.
     */
    public function updated(Faq $faq): void
    {
        $this->clearHomepageFaqCache();
    }

    /**
     * Handle the Faq "deleted" event.
     */
    public function deleted(Faq $faq): void
    {
        $this->clearHomepageFaqCache();
    }

    /**
     * Handle the Faq "restored" event.
     */
    public function restored(Faq $faq): void
    {
        $this->clearHomepageFaqCache();
    }

    /**
     * Helper function to clear the homepage FAQ cache.
     * এই মেথডটি কোড ডুপ্লিকেশন কমায় এবং রক্ষণাবেক্ষণ সহজ করে।
     */
    protected function clearHomepageFaqCache(): void
    {
        Cache::forget('homepage_faqs');
    }
}
