<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'role'  => $this->whenPivotLoaded('team_user', function () {
                return $this->pivot->role;
            }),
            'members' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
