<?php

namespace App\Observers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;

class MenuItemObserver
{
    /**
     * Handle events after all transactions are committed.
     * এটি নিশ্চিত করে যে ডাটাবেজ ট্রানজেকশন সফলভাবে শেষ হওয়ার পরেই এই কোডটি চলবে,
     * যা ডেটা ইনকনসিস্টেন্সি বা race condition প্রতিরোধ করে।
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the MenuItem "created" event.
     */
    public function created(MenuItem $menuItem): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the MenuItem "updated" event.
     */
    public function updated(MenuItem $menuItem): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the MenuItem "deleted" event.
     */
    public function deleted(MenuItem $menuItem): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the MenuItem "restored" event.
     */
    public function restored(MenuItem $menuItem): void
    {
        $this->clearMenuCache();
    }

    /**
     * Helper function to clear the main menu cache.
     * এই মেথডটি কোড ডুপ্লিকেশন কমায় এবং রক্ষণাবেক্ষণ সহজ করে।
     */
    protected function clearMenuCache(): void
    {
        Cache::forget('main_menu');
    }
}
