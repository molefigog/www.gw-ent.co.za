<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $buyer;
    public $payment_gross;
    public $payment_status;
    public $txn_id;
    public $productRow;

    public function __construct($buyer, $payment_gross, $payment_status, $txn_id, $productRow)
    {
        $this->buyer = $buyer;
        $this->payment_gross = $payment_gross;
        $this->payment_status = $payment_status;
        $this->txn_id = $txn_id;
        $this->productRow = $productRow;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Confirmation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }
   

    public function build()
    {
        return $this->view('emails.purchase_confirmation');
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
