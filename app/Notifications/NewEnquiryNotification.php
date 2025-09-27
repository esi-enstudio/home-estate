<?php

namespace App\Notifications;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEnquiryNotification extends Notification
{
    use Queueable;

    public Enquiry $enquiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
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
        // এই অ্যারেটি ডাটাবেজের 'data' কলামে JSON হিসেবে সেভ হবে
        return [
            'enquiry_id' => $this->enquiry->id,
            'property_title' => $this->enquiry->property->title,
            'enquirer_name' => $this->enquiry->name,
            'message' => "You have a new enquiry from {$this->enquiry->name} for your property.",
            // আপনি চাইলে অ্যাডমিন প্যানেলের একটি লিংকও দিতে পারেন
            'link' => route('filament.admin.resources.enquiries.view', $this->enquiry),
        ];
    }
}
