<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ActivityResource;


class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::whereIn('team_id', Auth::user()->teams->pluck('id'))
            ->withCount('tasks')
            ->get();

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
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
        
        $this->authorize('create', Project::class); 

        $project = Project::create($validated);

        return new ProjectResource($project);
    }

    public function activities(Project $project)
    {
        $this->authorize('view', $project);

        $activities = $project->activities()
            ->with(['user', 'subject'])
            ->paginate(10);

        return ActivityResource::collection($activities);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return new ProjectResource($project->load('tasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();
        return response()->noContent();
    }
}
