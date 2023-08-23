<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\PostLike;
use App\Notifications\CommentLoveNotification;
use App\Notifications\PostLikeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

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

        $postLike = auth()->user()->postsLikes->where('post_id', $postId)->first();
        $post = Post::with('author')->find($postId);

        if ($postLike) {
            $postLike->delete();
            $post->likes_num > 0 ? $post->decrement('likes_num') : '';

            return ApiResponse::send(200, 'Post Unliked successfully .', ['is_liked' => false]);
        } else {
            $post->increment('likes_num');
            $like = PostLike::create([
                'post_id' => $post->id,
                'user_id' => auth()->user()->id,
            ]);
            $like->likedAt = 'Post';
            Notification::send($post->author, new PostLikeNotification($post->id));

            return ApiResponse::send(201, 'Post liked successfully .', new LikeResource($like));
        }

    }

    private function likeComment($commentId)
    {
        $commentLike = auth()->user()->commentsLikes->where('comment_id', $commentId)->first();
        $comment = Comment::with('author')->find($commentId);

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
