<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['project_id' => 'required|exists:projects,id']);

        $tasks = Task::where('project_id', $request->project_id)
            ->with('assignee')
            ->orderBy('priority', 'desc')
            ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'status'      => 'in:todo,doing,review,done',
            'priority'    => 'in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,doing,review,done'
        ]);

        $task->update([
            'status' => $validated['status']
        ]);

        
        return response()->json([
            'message' => 'Task status updated',
            'task' => new TaskResource($task)
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
