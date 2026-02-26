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
        return $this->isTeamAdmin($user, $team);
    }

    public function removeMember(User $user, Team $team, User $memberToRemove): bool
    {
        return $this->isTeamAdmin($user, $team) || $user->id === $memberToRemove->id;
    }

    private function isTeamAdmin(User $user, Team $team): bool
    {
        return $team->users()
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }
}