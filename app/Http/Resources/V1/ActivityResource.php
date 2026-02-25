<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->whenLoaded('user', fn() => $this->user->name),
            ],
            'type' => $this->type,
            'description' => $this->formatDescription(),
            'subject_title' => $this->whenLoaded('subject', function() {
                return $this->subject?->title ?? 'Deleted Task';
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'human_date' => $this->created_at->diffForHumans(),
        ];
    }

    protected function formatDescription(): string
    {
        return match($this->type) {
            'task_created' => 'created a new task',
            'task_moved' => 'moved the task',
            'task_completed' => 'completed the task',
            'task_commented' => 'left a comment on',
            default => 'performed an action',
        };
    }
}
