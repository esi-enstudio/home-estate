<?php

namespace App\Observers;

use App\Models\Property;

class PropertyObserver
{
    /**
     * Handle the Property "saved" event.
     * Fires after a property is created or updated.
     */
    public function saved(Property $property): void
    {
        // স্কোর গণনা এবং সেট করার জন্য প্রাইভেট মেথডটিকে কল করুন
        $this->calculateAndSetScore($property);
    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        // If needed, you can handle logic when a property is deleted.
    }

    /**
     * Calculates the score of a property based on various criteria and updates it.
     *
     * @param Property $property The property instance.
     * @return void
     */
    private function calculateAndSetScore(Property $property): void
    {
        $score = 0;

        // --- Verification (Weight: Very High) ---
        if ($property->is_verified) $score += 50;
        if ($property->user?->is_verified) $score += 30;

        // --- Completeness & Media (Weight: High) ---
        if ($property->hasMedia('featured_image')) $score += 20;

        // Calculate score for gallery images (2 points per image, max 20 points)
        $galleryScore = $property->getMedia('gallery')->count() * 2;
        $score += min($galleryScore, 20);

        if (strlen($property->description) > 300) $score += 15;
        if (!empty($property->video_url)) $score += 15;
        if (!empty($property->faqs)) $score += 5;

        // --- Popularity (Weight: Dynamic) ---
        $score += ($property->average_rating * 5); // A 5-star rating adds 25 points
        $score += min($property->reviews_count, 10); // 1 point per review, max 10 points

        // --- Special Status (Weight: High) ---
        if ($property->is_featured) $score += 25;
        if ($property->is_trending) $score += 15;

        // --- Recency (Weight: Low) ---
        // Adds a bonus for properties created/updated in the last 7 days
        if ($property->updated_at->gt(now()->subDays(7))) {
            $score += 10;
        }

        // Update the score only if it has changed, and do it quietly
        // to prevent re-triggering the 'saved' observer event.
        if ($property->score !== $score) {
            Property::withoutEvents(function () use ($property, $score) {
                $property->score = $score;
                $property->save();
            });
            // Alternative using saveQuietly():
            // $property->score = $score;
            // $property->saveQuietly();
        }
    }
}
