<?php
namespace App\Actions\LikeActions;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Notification;

class LikePostAction
{
    public function execute(Post $post, User $user)
    {
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
