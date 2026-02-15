<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);

        $totalTasks = $project->tasks()->count();
        $doneTasks = $project->tasks()->where('status', 'done')->count();
        
        $completionPercentage = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100, 2) : 0;

        return response()->json([
            'project_name' => $project->name,
            'stats' => [
                'total_tasks' => $totalTasks,
                'done_tasks' => $doneTasks,
                'completion_rate' => $completionPercentage . '%',
                'high_priority_count' => $project->tasks()->where('priority', 'high')->count(),
                'overdue_tasks' => $project->tasks()
                    ->where('status', '!=', 'done')
                    ->where('due_date', '<', now())
                    ->count(),
            ]
        ]);
    }
}
