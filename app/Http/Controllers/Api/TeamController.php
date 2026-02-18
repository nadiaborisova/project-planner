<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->teams);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team = Team::create($validated);

        $team->users()->attach(Auth::id(), ['role' => 'admin']);

        return response()->json($team, 201);
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        return response()->json($team->load('users'));
    }

    public function addMember(Request $request, Team $team)
    {       
        $this->authorize('addMember', $team);

        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $userToAdd = User::where('email', $request->email)->first();

        if ($team->users()->where('user_id', $userToAdd->id)->exists()) {
            return response()->json(['message' => 'User is already in team'], 422);
        }

        $team->users()->attach($userToAdd->id, ['role' => 'member']);

        return response()->json(['message' => 'Member added successfully']);
    }
}