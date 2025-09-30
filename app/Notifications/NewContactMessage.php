<?php

namespace App\Notifications;

use App\Filament\Resources\MessageResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'icon' => 'heroicon-o-envelope',
            'message_id' => $this->message->id,
            'sender_name' => $this->message->name,
            'subject' => $this->message->subject,
            'message_text' => "আপনি {$this->message->name} এর কাছ থেকে একটি নতুন বার্তা পেয়েছেন।",
            'link' => MessageResource::getUrl('view', ['record' => $this->message->id]),
        ];
    }
}
