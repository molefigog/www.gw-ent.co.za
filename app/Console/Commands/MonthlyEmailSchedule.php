<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Music;
use App\Models\Setting;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Storage;

class MonthlyEmailSchedule extends Command
{
    protected $signature = 'email:monthly-schedule';

    protected $description = 'Send monthly email schedule to users with upload_status = 1';

    public function handle()
    {
        $users = User::where('upload_status', 1)->get();

        foreach ($users as $user) {
            // Eager load the songs relationship
            $songs = $user->musics()->get();

            // Calculate current month and year in PHP
            $currentMonth = now()->format('F');
            $currentYear = now()->format('Y');

            // Calculate grand total in PHP
            $grandTotal = $songs->sum(function ($song) {
                return $song->md * $song->amount;
            });

            // Send email with Blade view
            Mail::send('emails.monthly_schedule', [
                'user' => $user,
                'songs' => $songs,
                'currentMonth' => $currentMonth,
                'currentYear' => $currentYear,
                'grandTotal' => $grandTotal,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Tracks status');
            });
        }

        $this->info('Monthly schedule emails sent successfully.');
    }

}
