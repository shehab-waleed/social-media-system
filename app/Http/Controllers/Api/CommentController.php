<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($postId)
    {

        $post = Post::with('comments')->find($postId);
        if (! $post) {
            return ApiResponse::send(200, 'Post not found', []);
        }

        $comments = Comment::where('post_id', $postId)->with('author')->get();

        if (count($comments) > 0) {
            return ApiResponse::send(200, 'Comments retireved successfully .', CommentResource::collection($comments));
        } else {
            return ApiResponse::send(200, 'Post does not contains any comments', []);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        //
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $post = Post::find($data['post_id']);

        if (! $post) {
            return ApiResponse::send(200, 'Post not found', []);
        }

        $comment = Comment::create($data);

        Notification::send($post->author, new CommentNotification($data['post_id']));

        if ($comment) {
            return ApiResponse::send(200, 'Comment Created successfully . ', new CommentResource($comment));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commentNewData = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment = Comment::with('author')->find($id);
        if (! Auth()->user()->can('has-comment' , $comment)) {
            return ApiResponse::send(403, 'You are not allowed to take this action');
        }

        if (! $comment) {
            return ApiResponse::send(200, 'Comment not found', null);
        }

        $comment->update($commentNewData);

        return ApiResponse::send(200, 'Comment updated successfully .', new CommentResource($comment));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $comment = Comment::find($id);
        if (! $comment) {
            return ApiResponse::send(200, 'The comment does not exists. ', []);
        }

        if (!Auth()->user()->can('has-comment', $comment)) {
            return ApiResponse::send(403, 'You are not allow to take this action', []);
        }

        $comment->delete();

        return ApiResponse::send(200, 'Comment removed successfully . ');
    }
}
