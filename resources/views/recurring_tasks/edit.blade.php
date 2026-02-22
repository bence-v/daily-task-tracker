<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Recurring Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('recurring-tasks.update', ['recurring_task' => $recurringTask['id']]) }}" x-data="{ frequency: '{{ old('frequency', $recurringTask['frequency']->value) }}' }">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $recurringTask['title'])" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('description', $recurringTask['description']) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('Select Category') }}</option>

                                @foreach($categories as $categoryId => $category)
                                    <option value="{{ $categoryId }}" {{ old('category_id', $recurringTask['category']['id'] ?? null) == $categoryId ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Frequency -->
                        <div class="mt-4">
                            <x-input-label for="frequency" :value="__('Frequency')" />
                            <select id="frequency" name="frequency" x-model="frequency" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @foreach($frequencies as $freq)
                                    <option value="{{ $freq->value }}" {{ old('frequency', $recurringTask['frequency']->value) == $freq->value ? 'selected' : '' }}>{{ ucfirst($freq->value) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                        </div>

                        <!-- Weekly Config -->
                        <div class="mt-4" x-show="frequency === 'weekly'" style="display: none;">
                            <x-input-label :value="__('Days of Week')" />
                            <div class="flex flex-wrap gap-4 mt-2">
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="frequency_config[days][]" value="{{ $day }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ in_array($day, old('frequency_config.days', $recurringTask['frequency_config']['days'] ?? [])) ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ $day }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Monthly Config -->
                        <div class="mt-4" x-show="frequency === 'monthly'" style="display: none;">
                            <x-input-label for="day_of_month" :value="__('Day of Month')" />
                            <x-text-input id="day_of_month" class="block mt-1 w-full" type="number" min="1" max="31" name="frequency_config[day_of_month]" :value="old('frequency_config.day_of_month', $recurringTask['frequency_config']['day_of_month'] ?? '')" />
                        </div>

                        <!-- Start Date -->
                        <div class="mt-4">
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $recurringTask['start_date']?->toDateString() ?? null)" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mt-4">
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $recurringTask['end_date']?->toDateString() ?? null)" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('recurring-tasks.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
