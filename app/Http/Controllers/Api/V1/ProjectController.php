<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Project;
use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectResource;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::whereHas('team.users', function ($query): void {
                $query->where('users.id', Auth::id());
            })
            ->withCount('tasks')
            ->paginate();

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => [
                'required',
                Rule::exists('team_user', 'team_id')->where('user_id', Auth::id()),
            ],
            'description' => 'nullable|string',
        ]);
        
        $team = Team::findOrFail($validated['team_id']);
        
        $this->authorize('createProject', $team); 

        $project = Project::create($validated);

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return new ProjectResource($project->load('tasks'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();
        return response()->noContent();
    }
}
