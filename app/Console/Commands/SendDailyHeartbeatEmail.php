<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\HeartbeatEmail;
use App\Models\User;

class SendDailyHeartbeatEmail extends Command
{
    protected $signature = 'send:daily-heartbeat-email';
    protected $description = 'Send a daily heartbeat email to all users';

    public function handle()
    {
        // Get all users from the users table
        $users = User::all();

        // Loop through each user and send the heartbeat email
        foreach ($users as $user) {
            Mail::to($user->email)->send(new HeartbeatEmail($user));
        }

        $this->info('Heartbeat emails sent to all users successfully.');
    }
    public function build()
    {
        $user = $this->user; // Assuming you have a user variable available in your Mailable
    
        return $this->subject('Daily Heartbeat Email')
                    ->text("Hello, {$user->name}!\n\n".
                           "This is your daily heartbeat email. We're checking in to make sure everything is running smoothly.\n\n".
                           "If you have any questions or concerns, please don't hesitate to contact us.\n\n".
                           "Best regards,\nYour Company");
    }
}