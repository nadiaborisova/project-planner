<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $user->teams->contains('id', $task->project->team_id);
    }

    public function create(User $user, Project $project): bool
    {
        return $user->teams->contains('id', $project->team_id);
    }

    public function update(User $user, Task $task): bool
    {
        if (!$user->teams->contains('id', $task->project->team_id)) {
            return false;
        }

        return $this->isTeamAdmin($user, $task) || $user->id === $task->assigned_to;
    }

     public function delete(User $user, Task $task): bool
    {
        return $this->isTeamAdmin($user, $task);
    }

    private function isTeamAdmin(User $user, Task $task): bool
    {
        $team = $user->teams->firstWhere('id', $task->project->team_id);
    
        return $team && $team->pivot->role === 'admin';
    }
}