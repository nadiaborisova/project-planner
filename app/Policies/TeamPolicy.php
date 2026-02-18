<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;


class TeamPolicy
{
    public function view(User $user, Team $team): bool
    {
        return $team->users()->where('user_id', $user->id)->exists();
    }

    public function addMember(User $user, Team $team): bool
    {
        return $team->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}