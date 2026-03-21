<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Overdue Tasks Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Overdue Tasks
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white" id="overdue-count">
                            {{ $overdueCount }}
                        </div>
                    </div>
                </div>

                <!-- Completed Today Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Completed Today
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $completedTodayCount }} <span class="text-sm font-normal text-gray-500">/ {{ $totalTasksToday }}</span>
                        </div>
                    </div>
                </div>

                <!-- Completed Last 7 Days Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Completed (Last 7 Days)
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $completedLast7DaysCount }}
                        </div>
                    </div>
                </div>

                 <!-- Total Tasks Today Card -->
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total Tasks Today
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $totalTasksToday }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT AREA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT/TOP COLUMN (Overdue Tasks - Only shows if there are any) -->
                @if($overdueTasks->isNotEmpty())
                    <div class="lg:col-span-1">
                        <div class="bg-red-50 dark:bg-red-900/20 overflow-hidden shadow-sm sm:rounded-lg border border-red-200 dark:border-red-800">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-red-800 dark:text-red-400 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Action Required: Overdue
                                </h3>

                                <div class="space-y-4">
                                    @foreach($overdueTasks as $task)
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm flex items-start justify-between border-l-4 border-red-500" data-task-item data-completed="false">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                    {{ $task->title }}
                                                </p>
                                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                    Due: {{ $task->task_date->diffForHumans() }}
                                                </p>
                                                @if($task->category)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 mt-2">
                                                        {{ $task->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex-shrink-0 flex items-center gap-2">
                                                <button type="button" class="text-gray-400 hover:text-green-500 focus:outline-none" title="Mark Complete" data-task-toggle data-task-id="{{$task->uuid}}">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                                <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 hover:text-indigo-500">
                                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- RIGHT/BOTTOM COLUMN (Today's Tasks - expands if no overdue tasks) -->
                <div class="{{ $overdueTasks->isNotEmpty() ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Today's Tasks
                                </h3>
                                <a href="{{ route('tasks.create') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                    + Add New
                                </a>
                            </div>

                            @if($todayTasks->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">You have no tasks scheduled for today.</p>
                                    <p class="text-sm text-gray-400 mt-2">Enjoy your free time, or get ahead on future tasks!</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($todayTasks as $task)
                                        <div class="flex items-center justify-between p-4 rounded-lg border {{ $task->completed_at ? 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 shadow-sm' }}"
                                            data-task-item data-completed="{{ $task->completed_at ? 'true' : 'false' }}"
                                            >
                                            <div class="flex items-center space-x-4 flex-1">
                                                <!-- Checkbox Form -->
                                                    <button type="button" class="flex-shrink-0 focus:outline-none" data-task-toggle data-task-id="{{$task->uuid}}">
                                                        @if($task->completed_at)
                                                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @else
                                                            <svg class="h-6 w-6 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                <!-- Task Info -->
                                                <div class="flex-1 min-w-0">
                                                    <a href="{{ route('tasks.edit', $task) }}" class="block focus:outline-none">
                                                        <p class="text-sm font-medium truncate {{ $task->completed_at ? 'text-gray-500 line-through' : 'text-gray-900 dark:text-gray-100' }}">
                                                            {{ $task->title }}
                                                        </p>
                                                        @if($task->description)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5 {{ $task->completed_at ? 'line-through' : '' }}">
                                                                {{ $task->description }}
                                                            </p>
                                                        @endif
                                                    </a>
                                                </div>

                                                <!-- Category Badge -->
                                                @if($task->category)
                                                    <div class="hidden sm:block">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                            {{ $task->category->name }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
