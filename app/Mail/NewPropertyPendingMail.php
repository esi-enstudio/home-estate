<?php

namespace App\Mail;

use App\Models\Property;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPropertyPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Property $property) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'নতুন প্রপার্টি অনুমোদনের জন্য অপেক্ষারত: ' . $this->property->title,
        );
    }

    /**
     * Get the message content definition.
     * @throws Exception
     */
    public function content(): Content
    {
        $manageUrl = Filament::getPanel('superadmin')
            ->getResourceUrl(Property::class, 'edit', ['record' => $this->property]);

        return new Content(
            markdown: 'emails.new-property-pending',
            with: [
                'manageUrl' => $manageUrl,
            ]
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
