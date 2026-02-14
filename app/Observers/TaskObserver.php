<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $this->recordActivity($task, 'task_created');
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->isDirty('status')) {
            $this->recordActivity($task, 'task_moved');
        }
    }

    protected function recordActivity(Task $task, string $type): void
    {
        $userId = Auth::id() ?? (\App\Models\User::first()?->id);

        if ($userId) {
            Activity::create([
                'user_id'      => $userId,
                'type'         => $type,
                'subject_id'   => $task->id,
                'subject_type' => Task::class,
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
