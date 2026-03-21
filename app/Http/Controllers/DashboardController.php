<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->startOfDay();

        // 1. Static Cards Data
        $overdueCount = $user->tasks()
            ->whereNull('completed_at')
            ->whereDate('task_date', '<', $today)
            ->count();

        $completedTodayCount = $user->tasks()
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', '=', $today)
            ->count();

        $completedLast7DaysCount = $user->tasks()
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', '>=', now()->subDays(7)->startOfDay())
            ->count();

        $totalTasksToday = $user->tasks()
            ->whereDate('task_date', '=', $today)
            ->count();

        // 2. Overdue Tasks List
        $overdueTasks = $user->tasks()
            ->with('category')
            ->whereNull('completed_at')
            ->whereDate('task_date', '<', $today)
            ->orderBy('task_date', 'asc')
            ->get();

        // 3. Today's Tasks List
        $todayTasks = $user->tasks()
            ->with('category')
            ->whereDate('task_date', '=', $today)
            ->orderBy('completed_at', 'desc') // Show uncompleted first if completed_at is null
            ->orderBy('task_date', 'asc')
            ->get();

        return view('dashboard', compact(
            'overdueCount',
            'completedTodayCount',
            'completedLast7DaysCount',
            'totalTasksToday',
            'overdueTasks',
            'todayTasks'
        ));
    }
}
