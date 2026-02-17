<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Http\Resources\TaskResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $request->validate(['project_id' => 'required|exists:projects,id']);
        
        $project = Project::findOrFail($request->project_id);
        $this->authorize('view', $project);

        $tasks = $project->tasks()
            ->with('assignee')
            ->orderBy('priority', 'desc')
            ->get();

        return TaskResource::collection($tasks);
    }

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

        $project = Project::find($validated['project_id']);
        $this->authorize('update', $project);

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task->project);
        
        return new TaskResource($task->load('assignee'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task->project);

        $validated = $request->validate([
            'status' => 'required|in:todo,doing,review,done'
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Task status updated',
            'task' => new TaskResource($task)
        ]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task->project);

        $task->delete();
        return response()->noContent();
    }
}