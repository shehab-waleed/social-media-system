<?php

namespace App\Http\Controllers\Api;

use App\Actions\LikeActions\LikePostAction;
use App\Actions\LikeActions\UnlikePostAction;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostLike;
use App\Models\CommentLike;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\LikeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentLoveNotification;

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
        $post = Post::with('author')->findOrFail($postId);
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
        $commentLike = auth()->user()->commentsLikes->where('comment_id', $commentId)->first();
        $comment = Comment::with('author')->findOrFail($commentId);

        if ($commentLike) {
            $commentLike->delete();
            $comment->likes_num > 0 ? $comment->decrement('likes_num') : '';

            return ApiResponse::send(200, 'Comment Unliked successfully .', ['is_liked' => false]);
        } else {
            $comment->increment('likes_num');
            $like = CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => auth()->user()->id,
            ]);
            $like->likedAt = 'Comment';
            Notification::send($comment->author, new CommentLoveNotification($comment->id));

            return ApiResponse::send(201, 'Comment liked successfully .', new LikeResource($like));
        }
    }
}
