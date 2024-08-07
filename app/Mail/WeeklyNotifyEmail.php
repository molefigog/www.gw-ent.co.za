<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WeeklyNotifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $item; // Define $item property to pass data to the view

    public function __construct(User $user, $item)
    {
        $this->user = $user;
        $this->item = $item; // Initialize $item with the provided data
    }
  
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'WEEKLY REPORT',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view('emails.notify', ['item' => $this->item]) // Pass $item to the view
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
