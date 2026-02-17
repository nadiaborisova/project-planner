<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;


class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $user->teams->contains('id', $project->team_id);
    }

    public function create(User $user): bool
    {
        return $user->teams()->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->teams()
            ->where('teams.id', $project->team_id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}
