<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Music;

class ResetMdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset md field to 0 on the first day of every month at 1 am';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Logic to reset md field to 0 for all music records
        Music::query()->update(['md' => 0]);

        $this->info('md field reset successfully.');
    }
}
