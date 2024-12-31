<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        // Filter tasks by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sort tasks by due_date (default to ascending order)
        $sortOrder = $request->input('sort', 'asc');
        $query->orderBy('due_date', $sortOrder);

        // Get all tasks without pagination
        $tasks = $query->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'user_id' => Auth::id(),
        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    public function singe_task($id)
    {
        $task = Task::find($id);
    
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    
        return new TaskResource($task);
    }
    
}
