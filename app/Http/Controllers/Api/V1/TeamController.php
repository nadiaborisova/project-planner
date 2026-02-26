<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->teams;
        return TeamResource::collection($teams);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);

        $team = DB::transaction(function () use ($validated) {
            $team = Team::create($validated);
            $team->users()->attach(Auth::id(), ['role' => 'admin']);
            return $team;
        });

        return new TeamResource($team->load('users'));
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        return new TeamResource($team->load('users'));
    }
}