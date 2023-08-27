<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Notification;

class LikeService{
    public function storePostLike(Post $post ,User $user){

        $postLike = $post->likes()->where('parent_id' , $post->id);

        if ($postLike->count() > 0) {
            $postLike->delete();
            $post->likes_num > 0 ? $post->decrement('likes_num') : '';
            return false;

        } else {
            $post->increment('likes_num');
            $like = Like::create([
                'parent_type' => "App\Models\Post",
                'parent_id' => $post->id,
                'user_id' => $user->id
            ]);
            $like->likedAt = 'Post';
            Notification::send($post->author, new PostLikeNotification($post->id));

            return $like;
        }
    }
}
