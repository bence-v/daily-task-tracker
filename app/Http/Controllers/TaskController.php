<?php

namespace App\Http\Controllers;

use App\Actions\Category\GetCategories;
use App\Actions\Category\ResolveCategory;
use App\Actions\Task\CreateTask;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController
{
    public function __construct(private readonly GetCategories $getCategories)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->tasks()->with('category')->when($request->status === 'completed', fn($query) => $query->whereNotNull('completed_at'))
            ->when($request->status === 'incomplete', fn($query) => $query->whereNull('completed_at'))
            ->when($request->filled('category_id'), fn($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('date_from'), fn($query) => $query->whereDate('task_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($query) => $query->whereDate('task_date', '<=', $request->date_from))
            ->latest();


        $tasks = $query->paginate();
        $categories = $this->getCategories->execute($request->user()->id);

        return view('tasks.index', [
            'tasks' => $tasks->toResourceCollection()->resolve(),
            'links' => fn () => $tasks->links(),
            'categories' => $categories,
            'filters' => $request->only(['status', 'category_id','date_form','date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = $this->getCategories->execute($request->user()->id);

        return view('tasks.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, ResolveCategory $resolveCategory, CreateTask $createTask)
    {
        $createTask->execute($request->validated(), $request->user());

        return to_route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Task $task)
    {
        $task->load('category');

        $categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        return view('tasks.edit', ['task' => $task->toResource()->resolve(), 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task, ResolveCategory $resolveCategory)
    {
        $validData = $request->validated();
        $validData['category_id'] =  $resolveCategory->execute($validData['category_id'], $request->user());

        $task->fill($validData);
        $task->save();

        return to_route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return to_route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function toggleComplete(Task $task)
    {
        $task->update([
            'completed_at' => $task->completed_at ? null : now(),
        ]);

        return response()->json(['completed' => $task->completed_at !== null]);
    }
}
