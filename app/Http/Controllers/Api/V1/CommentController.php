<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CommentResource;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function index(Task $task)
    {
        $this->authorize('view', $task);

        $comments = $task->comments()
            ->with('user')
            ->latest()
            ->paginate(15);
        
        return CommentResource::collection($comments);
    }

    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'content' => 'required|string|min:2'
        ]);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content']
        ]);

        return new CommentResource($comment->load('user'));
    }

    public function update(Request $request, Task $task, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|min:2'
        ]);

        $comment->update($validated);

        return new CommentResource($comment->load('user'));
    }

    public function destroy(Task $task, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }
}