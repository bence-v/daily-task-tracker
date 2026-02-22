<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('auth:clear-resets')->daily();
Schedule::command('app:generate-recurring-tasks')->dailyAt('00:30')->runInBackground();
Schedule::command('app:archive-expired-recurring-tasks')->daily();
