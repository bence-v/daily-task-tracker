<?php

namespace App\Http\Controllers;

use App\Enums\TaskFrequency;
use App\Http\Requests\StoreRecurringTaskRequest;
use App\Http\Requests\UpdateRecurringTaskRequest;
use App\Models\Category;
use App\Models\RecurringTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class RecurringTaskController extends Controller
{
    public function index(Request $request)
    {
        $recurringTasks = $request->user()->recurringTasks()->with('category')->latest()->paginate(10);
        return view('recurring_tasks.index',[
            'recurringTasks' => $recurringTasks->toResourceCollection()->resolve(),
            'links' => fn () => $recurringTasks->links(),
        ]);
    }

    public function create(Request $request)
    {
        $frequencies = TaskFrequency::cases();
        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();
        return view('recurring_tasks.create', compact('categories', 'frequencies'));
    }

    public function store(StoreRecurringTaskRequest $request)
    {
        $taskData = $request->validated();

        if($request->category_id) {
            $category = Category::where('uuid', $request->category_id)->first();

            if(! $category || $request->user()->cannot('manage',$category)) {
                throw ValidationException::withMessages(['category_id' => 'the given category id does not exist']);
            }

            $taskData['category_id'] = $category->id;
        }

        $request->user()->recurringTasks()->create($taskData);

        return to_route('recurring-tasks.index')->with('success', 'Recurring task created successfully.');
    }

    public function edit(Request $request, RecurringTask $recurringTask)
    {
        $recurringTask->load('category');
        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();
        $frequencies = TaskFrequency::cases();

        return view('recurring_tasks.edit',[
            'recurringTask' => $recurringTask->toResource()->resolve(),
            'categories' => $categories,
            'frequencies' => $frequencies,
        ]);
    }

    public function update(UpdateRecurringTaskRequest $request, RecurringTask $recurringTask)
    {
        $validData = $request->validated();

        if($request->category_id) {
            $category = Category::where('uuid', $request->category_id)->first();

            if(! $category || $request->user()->cannot('manage',$category)) {
                throw ValidationException::withMessages(['category_id' => 'the given category id does not exist']);
            }

            $validData['category_id'] = $category->id;

            if(! $validData['category_id'] ) {
                throw ValidationException::withMessages(['category_id' => 'The given category id does not exist.']);
            }

            $recurringTask->category()->associate($category);

            unset($validData['category_id']);
        }

        $recurringTask->fill($validData);
        $recurringTask->save();

        return to_route('recurring-tasks.index')->with('success', 'Recurring task updated successfully.');
    }

    public function destroy(RecurringTask $recurringTask)
    {
        $recurringTask->delete();

        return to_route('recurring-tasks.index')->with('success', 'Recurring task deleted successfully.');
    }
}
