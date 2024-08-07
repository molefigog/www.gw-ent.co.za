<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Beat;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BeatDownloadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $beat;
    private $buyer;


    public function __construct(Beat $beat, User $buyer)
    {
        $this->beat = $beat;
        $this->buyer = $buyer;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)

    {
        Log::info('Attempting to send Beat download notification email to: ' . $notifiable->email);

        try {
            return (new MailMessage)
                ->subject('Beat Purchased Notification')
                ->line('Your Beat item has been purchased.')
                ->line('Date and Time of Purchase: ' . now()->format('Y-m-d H:i:s')) // Include date and time
                ->line('Buyer Name: ' . $this->buyer->name) // Include the name of the buyer
                ->action('View Beat', route('beat.slug', ['slug' => urlencode($this->beat->slug)]));
            Log::info('Music download notification email sent successfully.');
        } catch (\Exception $e) {
            Log::error('Error sending music download notification email: ' . $e->getMessage());
            throw $e; // Re-throw the exception to propagate it
        }
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function failed(\Exception $exception)
    {
        Log::error('Notification failed to deliver: ' . $exception->getMessage());
    }
}
