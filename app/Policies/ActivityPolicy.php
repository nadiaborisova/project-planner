<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Activity $activity): bool
    {
        return $user->teams()
            ->where('teams.id', $activity->project_id)
            ->exists();
    }

    public function create(User $user): bool
    {
        return false; 
    }

    public function delete(User $user, Activity $activity): bool
    {
        return $user->teams()
            ->where('teams.id', $activity->project_id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}
