<?php

namespace App\Notifications;

use App\Filament\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPropertyPending extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Property $property) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $owner = $this->property->user;

        return [
            'icon' => 'heroicon-o-home-modern',
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'owner_name' => $owner->name,
            'message' => "{$owner->name} একটি নতুন প্রপার্টি '{$this->property->title}' জমা দিয়েছেন, যা অনুমোদনের জন্য অপেক্ষারত।",
            'link' => PropertyResource::getUrl('edit', ['record' => $this->property]),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
