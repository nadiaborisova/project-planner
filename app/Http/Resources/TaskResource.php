<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request)
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'status' => $this->status,
        'priority' => $this->priority,
        'due_date' => $this->due_date ? $this->due_date->format('Y-m-d') : null,
        'assignee' => new UserResource($this->whenLoaded('assignee')),
        'created_at' => $this->created_at->diffForHumans(),
    ];
}
}
