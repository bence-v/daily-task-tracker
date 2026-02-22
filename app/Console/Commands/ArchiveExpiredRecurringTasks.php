<?php

namespace App\Console\Commands;

use App\Models\RecurringTask;
use Illuminate\Console\Command;

class ArchiveExpiredRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:archive-expired-recurring-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive recurring tasks that have passeed their end date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = RecurringTask::query()->whereNotNull('end_date')->where('end_date', '<', today())->delete();

        if($expired > 0) {
            $this->info('Archived ' . $expired . ' expired recurring tasks.');
        } else {
            $this->info('There are no expired recurring tasks to archive.');
        }
    }
}
