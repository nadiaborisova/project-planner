<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project)
    {
        $this->authorize('view', $project);

        $stats = $project->tasks()
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when status = 'done' then 1 end) as done")
            ->selectRaw("count(case when priority = 'high' then 1 end) as high_priority")
            ->selectRaw("count(case when status != 'done' and due_date < ? then 1 end) as overdue", [now()])
            ->first();

        $totalTasks = $stats->total;
        $doneTasks = $stats->done;

        $completionPercentage = $totalTasks > 0 
            ? round(($doneTasks / $totalTasks) * 100, 2) 
            : 0;

        return response()->json([
            'project_name' => $project->name,
            'stats' => [
                'total_tasks' => (int) $totalTasks,
                'done_tasks' => (int) $doneTasks,
                'completion_rate' => $completionPercentage . '%',
                'high_priority_count' => (int) $stats->high_priority,
                'overdue_tasks' => (int) $stats->overdue,
            ]
        ]);
    }
}
