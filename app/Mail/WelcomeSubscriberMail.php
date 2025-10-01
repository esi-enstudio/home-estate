<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeSubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * PHP 8 Constructor Property Promotion ব্যবহার করা হয়েছে।
     *
     * @param Subscriber $subscriber
     */
    public function __construct(public Subscriber $subscriber)
    {
        //
    }

    /**
     * Get the message envelope.
     * ইমেইলের বিষয় (Subject) এবং অন্যান্য তথ্য এখানে ডিফাইন করা হয়।
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'স্বাগতম! আমাদের নিউজলেটারে আপনাকে ধন্যবাদ',
        );
    }

    /**
     * Get the message content definition.
     * ইমেইলের বডি কোন ভিউ থেকে আসবে তা এখানে বলা হয়।
     */
    public function content(): Content
    {
        // আমরা Laravel-এর সুন্দর এবং রেসপন্সিভ Markdown ইমেইল টেমপ্লেট ব্যবহার করছি
        return new Content(
            markdown: 'emails.welcome-subscriber',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
