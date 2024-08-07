<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MusicDownloadNotification;
use App\Models\User;
use App\Models\Items;
use App\Models\Music;
use Illuminate\Support\Facades\Log;

class WeeklyEmails extends Command
{
    protected $signature = 'weekly:emails';
    protected $description = 'Send download notification email to music uploaders weekly';

    public function handle()
    {
        $this->info('Starting the weekly:emails command.');

        // Get a list of items that need download notifications (you need to define this logic)
        $items = Items::where('downloads', true)->get();

        foreach ($items as $item) {
            // Fetch the uploader's user model based on the uploader_id
            $uploader = User::find($item->uploader_id);

            if ($uploader) {
                // Fetch the music model based on the music_id
                $music = Music::find($item->music_id);

                if ($music) {
                    try {
                        // Notify the uploader about the purchase
                        $uploader->notify(new MusicDownloadNotification($music, $uploader));

                        $this->info("Download notification email sent to {$uploader->email} successfully.");
                    } catch (\Exception $e) {
                        // Log the error if sending fails
                        Log::error("Error sending download notification email to {$uploader->email}: {$e->getMessage()}");
                        $this->error("Failed to send download notification email for item ID {$item->id}.");
                    }
                } else {
                    $this->error("Failed to send download notification email for item ID {$item->id}: Music not found.");
                }
            } else {
                $this->error("Failed to send download notification email for item ID {$item->id}: Uploader not found.");
            }
        }
    }
}
