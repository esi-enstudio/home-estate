<?php

namespace App\Observers;

use App\Models\PropertyType;
use Illuminate\Support\Facades\Cache;

class PropertyTypeObserver
{
    /**
     * Handle events after all transactions are committed.
     * এটি নিশ্চিত করে যে ডাটাবেজ ট্রানজেকশন সফলভাবে শেষ হওয়ার পরেই এই কোডটি চলবে,
     * যা ডেটা ইনকনসিস্টেন্সি বা race condition প্রতিরোধ করে।
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the PropertyType "created" event.
     * নতুন কোনো প্রপার্টি টাইপ তৈরি হওয়ার পর।
     */
    public function created(PropertyType $propertyType): void
    {
        $this->clearCache();
    }

    /**
     * Handle the PropertyType "updated" event.
     * কোনো প্রপার্টি টাইপ আপডেট (যেমন নাম পরিবর্তন) হওয়ার পর।
     */
    public function updated(PropertyType $propertyType): void
    {
        $this->clearCache();
    }

    /**
     * Handle the PropertyType "deleted" event.
     * কোনো প্রপার্টি টাইপ ডিলিট হওয়ার পর।
     */
    public function deleted(PropertyType $propertyType): void
    {
        $this->clearCache();
    }

    /**
     * Handle the PropertyType "restored" event.
     * কোনো soft-deleted প্রপার্টি টাইপ পুনরুদ্ধার হওয়ার পর।
     */
    public function restored(PropertyType $propertyType): void
    {
        $this->clearCache();
    }

    /**
     * Helper function to clear all related caches.
     * এই মেথডটি কোড ডুপ্লিকেশন কমায় এবং রক্ষণাবেক্ষণ সহজ করে।
     */
    private function clearCache(): void
    {
        // "Property Types" Livewire কম্পোনেন্টে ব্যবহৃত ক্যাশ কী
        Cache::forget('popular_property_types');

        // ভবিষ্যতে যদি অন্য কোনো ক্যাশ PropertyType এর উপর নির্ভরশীল হয়,
        // তাহলে আপনি শুধু এখানেই Cache::forget('another_key') যোগ করবেন।
    }
}
