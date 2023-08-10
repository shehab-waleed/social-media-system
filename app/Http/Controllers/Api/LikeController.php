<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentLikeRequest;
use App\Http\Requests\PostLikeRequest;
use App\Http\Resources\CommentLikeResource;
use App\Http\Resources\PostLikeResource;
use App\Models\CommentLike;
use App\Models\PostLike;
use App\Models\Post;

class LikeController extends Controller
{
    public function likePost(PostLikeRequest $request)
    {
        // Get the authenticated user
        $user = $request->user();
        // Get the post ID from the request
        $postId = $request->post_id;

        // Check if the user has already liked the post
        $existingLike = PostLike::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        if ($existingLike) {
            // User unlikes the post
            $existingLike->delete();
            // Decrement the likes count for the post
            $this->decrementLikesCount($postId);
            return ApiResponse::send(200, 'You unliked the Post', []);
        } else {
            // User likes the post
            $like = new PostLike([
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);

            if ($like->save()) {
                // Increment the likes count for the post
                $this->incrementLikesCount($postId);
                return ApiResponse::send(200, 'You liked the Post', new PostLikeResource($like));
            }

            return ApiResponse::send(500, 'Some error occurred, please try again');
        }
    }
    public function likeComment(CommentLikeRequest $request) {
        // Get the authenticated user
        $user = $request->user();
        // Get the comment ID from the request
        $commentId = $request->comment_id;

        // Check if the user has already liked the post
        $existingLike = CommentLike::where('user_id', $user->id)
            ->where('comment_id', $commentId)
            ->first();

        if ($existingLike) {
            // User unlikes the comment
            $existingLike->delete();
            return ApiResponse::send(200, 'You unliked the comment', []);
        } else {
            // User likes the post
            $like = new CommentLike([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ]);

            if ($like->save()) {
                return ApiResponse::send(200, 'You liked the comment', new CommentLikeResource($like));
            }

            return ApiResponse::send(500, 'Some error occurred, please try again');
        }
    }

    // Increment the likes count for the specified post
    private function incrementLikesCount($postId)
    {
        $post = Post::find($postId);
        if ($post) {
            $post->increment('likes_num');
        }
    }

    // Decrement the likes count for the specified post
    private function decrementLikesCount($postId)
    {
        $post = Post::find($postId);
        if ($post) {
            $post->decrement('likes_num');
        }
    }
}
