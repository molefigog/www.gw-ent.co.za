<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\Music;
use App\Models\User;

class AlertMail extends Notification
{
    use Queueable;

    use Queueable;

    private $music;
    private $productUser;


    public function __construct(Music $music, User $productUser)
    {
        $this->music = $music;
        $this->productUser = $productUser;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)

    {
        Log::info('Attempting to send music download notification email to: ' . $notifiable->email);

        try {
            return (new MailMessage)
                ->subject('Music Purchased Notification')
                ->line('Your music item has been purchased.')
                ->line('Date and Time of Purchase: ' . now()->format('Y-m-d H:i:s')) // Include date and time
                ->line('Buyer Name: ' . $this->productUser->name) // Include the name of the buyer
                ->action('View Music', route('msingle.slug', ['slug' => urlencode($this->music->slug)]));
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


