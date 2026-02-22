<?php

namespace App\Policies;

use App\Models\RecurringTask;
use App\Models\Task;
use App\Models\User;

class RecurringTaskPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function manage(User $user, RecurringTask $recurringTask): bool
    {
        return $recurringTask->user()->is($user);
    }
}
