<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;


class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->teams->contains($task->project->team_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->teams()->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        $isMember = $user->teams->contains($task->project->team_id);
        $isAssignee = $user->id === $task->assigned_to;
        
        // Можеш да добавиш и проверка за роля 'admin' тук
        return $isMember && ($isAssignee || $this->isTeamAdmin($user, $task));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $this->isTeamAdmin($user, $task);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
    private function isTeamAdmin(User $user, Task $task): bool
    {
        $team = $user->teams->firstWhere('id', $task->project->team_id);
    
        return $team && $team->pivot->role === 'admin';
    }
}
