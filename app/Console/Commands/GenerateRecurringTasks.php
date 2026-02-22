<?php

namespace App\Console\Commands;

use App\Enums\TaskFrequency;
use App\Models\RecurringTask;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GenerateRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-recurring-tasks
     {date? : The date to generate the task for}
     {-- force : Force generation even if task(s) for the day already exists}
     {--user= : Only generate for a specific user ID}
     ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = today();

        $recurringTasksQuery = RecurringTask::query()
            ->where(fn($query) => $query->whereNull('start_date')->orWhere('start_date', '<=', $targetDate))
            ->where(fn($query) => $query->whereNull('end_date')->orWhere('end_date', '>=', $targetDate))
            ->whereDoesntHave('tasks', fn($q) => $q->whereDate('task_date', $targetDate));

        if(! ($totalRecurringTasks = $recurringTasksQuery->count())) {
            $this->info('No active recurring tasks found.');

            return self::SUCCESS;
        }

        $created = 0;
        $skipped = 0;

        $recurringTasksQuery->chunkById(250, function(Collection $recurringTasks) use ($targetDate, &$skipped, &$created) {
            try {
                $insertTasksBatch = [];

                foreach ($recurringTasks as $recurringTask) {
                    try {
                        if (!$this->isRecurringTaskDue($recurringTask, $targetDate)) {
                            $skipped++;
                            continue;
                        }

                        $now = new \DateTime();

                        $insertTasksBatch[] =
                            [
                                'uuid' => (string)Str::uuid7(),
                                'user_id' => $recurringTask->user_id,
                                'category_id' => $recurringTask->category_id,
                                'title' => $recurringTask->title,
                                'description' => $recurringTask->description,
                                'recurring_task_id' => $recurringTask->id,
                                'task_date' => $targetDate,
                                'created_at' => $now,
                            ];
                    } catch (\Exception $e) {
                        report($e);
                    }
                }

                if($insertTasksBatch) {
                    Task::insert($insertTasksBatch);
                    $created += count($insertTasksBatch);
                }

            } catch (\Exception $e) {
                report($e);
            }

        });


        $this->info('Processing ' . $totalRecurringTasks . ' recurring tasks...');


        $this->info('Created '. $created . ' recurring tasks.');

        if($skipped > 0) {
            $this->info('Skipped '. $skipped . ' recurring tasks.');
        }

        $this->newLine();

        return self::SUCCESS;
    }

    private function isRecurringTaskDue(RecurringTask $recurringTask, Carbon $targetDate)
    {
        return match($recurringTask->frequency) {
            TaskFrequency::Daily => true,
            TaskFrequency::WeekDays => $targetDate->isWeekday(),
            TaskFrequency::Weekly => $this->isWeeklyRecurringTaskDue($recurringTask, $targetDate),
            TaskFrequency::Monthly => $this->isMonthlyRecurringTaskDue($recurringTask, $targetDate),
            default => false,
        };
    }

    private function isWeeklyRecurringTaskDue(RecurringTask $recurringTask, Carbon $targetDate)
    {
        $config = $recurringTask->frequency_config;

        if(! $config || ! isset($config['days'])) {
            return false;
        }

        return in_array(strtolower($targetDate->engilshDayOfWeek), $config['days']);

    }

    private function isMonthlyRecurringTaskDue(RecurringTask $recurringTask, Carbon $targetDate)
    {
        $config = $recurringTask->frequency_config;

        if(! $config || ! isset($config['days'])) {
            return false;
        }

        $dayOfMonth = min($config['days'], $targetDate->daysInMonth);

        return $targetDate->day == $dayOfMonth;
    }
}
