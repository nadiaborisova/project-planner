<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Http\Resources\V1\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{
    public function store(Request $request, Team $team)
    {
        $this->authorize('addMember', $team);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $userToAdd = User::where('email', $validated['email'])->first();

        if ($team->users()->where('user_id', $userToAdd->id)->exists()) {
            return response()->json(['message' => 'User is already in team'], 422);
        }

        $team->users()->attach($userToAdd->id, ['role' => 'member']);

        return (new TeamResource($team->load('users')))
            ->additional(['message' => 'Member added successfully']);
    }

    public function destroy(Team $team, User $user)
    {
        $this->authorize('removeMember', [$team, $user]);

        if ($user->id === Auth::id() && $team->users()->where('role', 'admin')->count() === 1) {
            return response()->json(['message' => 'You cannot leave the team as the sole admin'], 422);
        }

        $team->users()->detach($user->id);

        return response()->noContent();
    }
}