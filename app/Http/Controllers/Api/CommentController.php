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
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($postId)
    {
        $post = Post::with('comments')->findOrFail($postId);
        $comments = Comment::where('post_id', $postId)->with('author')->get();

        if (count($comments) > 0) {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Comments retireved successfully .', CommentResource::collection($comments));
        } else {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'Post does not contains any comments', []);
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

        $post = Post::findOrFail($data['post_id'])->with('author')->first();

        $comment = Comment::create($data);

        Notification::send($post->author, new CommentNotification($data['post_id']));

        if ($comment) {
            return ApiResponse::send(JsonResponse::HTTP_CREATED, 'Comment Created successfully . ', new CommentResource($comment));
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

        $comment = Comment::with('author')->findOrFail($id);
        if (! Auth()->user()->can('has-comment', $comment)) {
            return ApiResponse::send(JsonResponse::HTTP_FORBIDDEN, 'You are not allowed to take this action');
        }

        $comment->update($commentNewData);

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Comment updated successfully .', new CommentResource($comment));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $comment = Comment::findOrFail($id);

        if (! Auth()->user()->can('has-comment', $comment)) {
            return ApiResponse::send(JsonResponse::HTTP_FORBIDDEN, 'You are not allow to take this action', []);
        }

        $comment->delete();

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Comment removed successfully . ');
    }
}
