<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Activity;
use App\Models\Task;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        Activity::create([
            'user_id'      => $comment->user_id,
            'project_id'   => $comment->task->project_id,
            'type'         => 'task_commented',
            'subject_id'   => $comment->task_id,
            'subject_type' => Task::class,
        ]);
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        Activity::where('type', 'task_commented')
            ->where('subject_id', $comment->task_id)
            ->where('user_id', $comment->user_id)
            ->latest()
            ->first()
            ?->delete();
    }
}
