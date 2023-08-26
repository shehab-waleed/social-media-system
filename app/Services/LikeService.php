<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Notification;

class LikeService{
    public function storePostLike(Post $post ,User $user){

        $postLike = $user->postsLikes->where('post_id', $post->id)->first();

        if ($postLike) {
            $postLike->delete();
            $post->likes_num > 0 ? $post->decrement('likes_num') : '';
            return false;

        } else {
            $post->increment('likes_num');
            $like = PostLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $like->likedAt = 'Post';
            Notification::send($post->author, new PostLikeNotification($post->id));

            return $like;
        }
    }
}
