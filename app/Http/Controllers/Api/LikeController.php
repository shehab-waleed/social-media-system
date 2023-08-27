<?php

namespace App\Http\Controllers\Api;

use App\Actions\LikeActions\Comment\LikeCommentAction;
use App\Actions\LikeActions\Comment\UnlikeCommentAction;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentLoveNotification;
use App\Actions\LikeActions\Post\LikePostAction;
use App\Actions\LikeActions\Post\UnlikePostAction;

class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $likeType = Route::currentRouteName() === 'post.like' ? 'post' : 'comment';
        $tableName = $likeType === 'post' ? 'posts' : 'comments';
        $itemIdKey = "{$likeType}_id";

        $attributes = $request->validate([
            $itemIdKey => ['required', "exists:$tableName,id"],
        ]);

        return ($likeType === 'post') ? $this->likePost($attributes['post_id']) : $this->likeComment($attributes['comment_id']);

    }

    private function likePost($postId)
    {
        $post = Post::with('author' , 'likes')->findOrFail($postId);
        $postLike = $post->likes()->where('parent_id', $post->id);

        if ($postLike->count() == 0) {
            $like = (new LikePostAction)->execute($post, Auth::user());
            return ApiResponse::send(201, 'Post liked successfully .', new LikeResource($like));
        } else {
            (new UnlikePostAction)->execute($postLike, $post);
            return ApiResponse::send(200, 'Post Unliked successfully .', ['is_liked' => false]);
        }
    }

    private function likeComment($commentId)
    {
        $comment = Comment::with('author' , 'likes')->findOrFail($commentId);
        $commentLike = $comment->likes()->where('parent_id', $comment->id);

        if ($commentLike->count() == 0) {
            $like = (new LikeCommentAction)->execute($comment, Auth::user());
            return ApiResponse::send(201, 'Comment liked successfully .', new LikeResource($like));
        } else {
            (new UnlikeCommentAction)->execute($commentLike, $comment);
            return ApiResponse::send(200, 'Comment Unliked successfully .', ['is_liked' => false]);
        }
    }
}
