<?php

namespace App\Observers;

use App\Models\Property;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;

class ReviewObserver
{
    /**
     * Handle events after all transactions are committed.
     * এটি নিশ্চিত করে যে ডাটাবেজ ট্রানজেকশন সফলভাবে শেষ হওয়ার পরেই এই কোডটি চলবে।
     * @var bool
     */
    public bool $afterCommit = true;

    // --- Create, Delete, Restore Events ---
    // এই ইভেন্টগুলোতে সবসময়ই পরিসংখ্যান আপডেট করা প্রয়োজন।

    public function created(Review $review): void
    {
        // যদি রিভিউটি 'approved' হিসেবে তৈরি হয়, তবেই কেবল পরিসংখ্যান আপডেট হবে।
        if ($review->status === 'approved') {
            $this->updatePropertyReviewStats($review->property);
            $this->clearHomepageCaches();
        }
    }

    public function deleted(Review $review): void
    {
        // যদি একটি 'approved' রিভিউ ডিলিট করা হয়, তবেই কেবল পরিসংখ্যান আপডেট হবে।
        if ($review->status === 'approved') {
            $this->updatePropertyReviewStats($review->property);
            $this->clearHomepageCaches();
        }
    }

    public function restored(Review $review): void
    {
        // যদি একটি 'approved' রিভিউ পুনরুদ্ধার করা হয়, তবেই কেবল পরিসংখ্যান আপডেট হবে।
        if ($review->status === 'approved') {
            $this->updatePropertyReviewStats($review->property);
            $this->clearHomepageCaches();
        }
    }

    // --- Update Event (সবচেয়ে গুরুত্বপূর্ণ পরিবর্তন এখানে) ---
    public function updated(Review $review): void
    {
        // === ধাপ ১: প্রপার্টির রিভিউ পরিসংখ্যান আপডেট করা ===
        // শুধুমাত্র যদি 'status' কলামটি পরিবর্তন হয়ে থাকে, তবেই পরিসংখ্যান আপডেট হবে।
        if ($review->wasChanged('status')) {
            $this->updatePropertyReviewStats($review->property);

            // যদি স্ট্যাটাস 'approved' হয় বা 'approved' থেকে অন্য কিছু হয়,
            // তাহলে হোমপেজের ক্যাশগুলো রিসেট করুন।
            $this->clearHomepageCaches();
        }

        // === ধাপ ২: হোমপেজের Testimonial ক্যাশ রিসেট করা ===
        // শুধুমাত্র যদি 'is_testimonial' ফ্ল্যাগটি পরিবর্তন হয়ে থাকে,
        // তাহলে হোমপেজের প্রশংসাপত্রের ক্যাশটি মুছে ফেলুন।
        if ($review->wasChanged('is_testimonial')) {
            Cache::forget('customer_testimonials_stats');
        }
    }

    /**
     * Helper function to update the review statistics for a given property.
     */
    protected function updatePropertyReviewStats(?Property $property): void
    {
        // যদি কোনো কারণে প্রপার্টি খুঁজে পাওয়া না যায়, তাহলে ফাংশনটি কিছুই করবে না।
        if (!$property) {
            return;
        }

        // শুধুমাত্র অনুমোদিত রিভিউগুলো গণনা করা হবে
        $approvedReviewsQuery = $property->reviews()->where('status', 'approved');

        $property->updateQuietly([
            'reviews_count' => $approvedReviewsQuery->count(),
            'average_rating' => $approvedReviewsQuery->avg('rating') ?? 0.0,
        ]);
    }

    /**
     * Helper function to clear all homepage related caches.
     * এই মেথডটি কোডকে পরিষ্কার এবং রক্ষণাবেক্ষণযোগ্য করে তোলে।
     */
    private function clearHomepageCaches(): void
    {
        Cache::forget('home_banner_rating_stats');
        Cache::forget('home_banner_recent_reviewers');
        Cache::forget('customer_testimonials_stats'); // এটিকেও এখানে রাখা ভালো
    }
}
