<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReviewMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Review $review)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "আপনার প্রপার্টির জন্য একটি নতুন রিভিউ এসেছে: '{$this->review->property->title}'",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // আমরা Laravel-এর সুন্দর Markdown ইমেইল টেমপ্লেট ব্যবহার করছি
        return new Content(
            markdown: 'emails.new-review',
        );
    }
}
