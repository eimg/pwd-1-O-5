<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $post->comments()->create([
            'content' => $request->validated('content'),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Comment added.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('posts.show', $post)->with('success', 'Comment deleted.');
    }
}
