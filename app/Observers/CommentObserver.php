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
            'type'         => 'task_commented',
            'subject_id'   => $comment->task_id,
            'subject_type' => Task::class,
        ]);
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
