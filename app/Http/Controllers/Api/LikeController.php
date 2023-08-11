<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Resources\LikeResource;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\PostLike;
use Illuminate\Support\Facades\Route;

class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $likeAt = Route::currentRouteName() === 'post.like' ? 'post' : 'comment';
        $table = $likeAt === 'post' ? 'posts' : 'comments';

        $attributes = $request->validate([
            "$likeAt" . '_id' => ['required', "exists:$table,id"],
        ]);

        if ($likeAt === 'post')
            return $this->likePost($attributes['post_id']);
        else
            return $this->likeComment($attributes['comment_id']);

    }

    private function likePost($postId)
    {

        $postLike = auth()->user()->postsLikes->where('post_id', $postId)->first();
        $post = Post::find($postId);
        if ($postLike) {
            $postLike->delete();
            $post->likes_num > 0 ? $post->decrement('likes_num') : '';
            return ApiResponse::send(200, 'Post Unliked successfully .', null);
        } else {
            $post->increment('likes_num');
            $like = PostLike::create([
                'post_id' => $post->id,
                'user_id' => auth()->user()->id
            ]);
            $like->likedAt = 'Post';

            return ApiResponse::send(201, 'Post liked successfully .', new LikeResource($like));
        }

    }

    private function likeComment($commentId)
    {
        $commentLike = auth()->user()->commentsLikes->where('comment_id', $commentId)->first();
        $comment = Comment::find($commentId);
        if ($commentLike) {
            $commentLike->delete();
            $comment->likes_num > 0 ? $comment->decrement('likes_num') : '';
            return ApiResponse::send(200, 'Comment Unliked successfully .', null);
        } else {
            $comment->increment('likes_num');
            $like = CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => auth()->user()->id
            ]);
            $like->likedAt = 'Comment';

            return ApiResponse::send(201, 'Comment liked successfully .', new LikeResource($like));
        }
    }
}
