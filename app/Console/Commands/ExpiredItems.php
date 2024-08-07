<?php

namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Items;

class ExpiredItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete items that have been created for more than 7 days.';

    /**
     * Execute the console command. 
     */
    public function handle()
{
    $tenDaysAgo = now()->subDays(10);

    // Delete items older than 10 days
    \App\Models\Items::where('created_at', '<=', $tenDaysAgo)->delete();

    $this->info('Expired items have been deleted successfully.');
}
   
}
