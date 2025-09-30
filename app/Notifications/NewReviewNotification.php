<?php

namespace App\Notifications;

use App\Filament\Resources\PropertyResource; // <-- রিসোর্স ক্লাসটি ইম্পোর্ট করুন
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     * PHP 8 Constructor Property Promotion ব্যবহার করা হয়েছে।
     */
    public function __construct(public Review $review)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // আমরা এই নোটিফিকেশনটি শুধুমাত্র ডাটাবেসে সেভ করতে চাই
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * এই ডেটাগুলো ডাটাবেজের 'data' কলামে JSON হিসেবে সেভ হবে।
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $property = $this->review->property;
        $reviewer = $this->review->user;

        // Filament প্যানেলের নির্দিষ্ট প্রপার্টির রিভিউ ট্যাবে যাওয়ার জন্য ডাইনামিক লিংক
        // ReviewsRelationManager এর index হলো 1 (0 = Enquiries, 1 = Reviews)
        $link = PropertyResource::getUrl('edit', ['record' => $property]) . '?activeRelationManager=1';

        return [
            'icon' => 'heroicon-o-chat-bubble-left-right', // Filament এ দেখানোর জন্য আইকন
            'review_id' => $this->review->id,
            'property_title' => $property->title,
            'reviewer_name' => $reviewer->name,
            'rating' => $this->review->rating,
            'message' => "আপনার প্রপার্টি '{$property->title}'-এ {$reviewer->name} একটি নতুন রিভিউ দিয়েছেন।",
            'link' => $link,
        ];
    }
}
