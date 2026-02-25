<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ActivityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
    /**
     * Get all activities for a specific project.
     */
    public function index(Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        $activities = $project->activities()
            ->with(['user', 'subject'])
            ->latest()
            ->paginate(20);

        return ActivityResource::collection($activities);
    }
}
