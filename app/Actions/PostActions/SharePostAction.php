<?php

namespace App\Actions\PostActions;

use App\Models\Post;
use App\Models\User;

class SharePostAction
{
    public function execute(User $user, Post $post)
    {
        $newPost = $post->replicate();
        $post->increment('num_of_shares');
        $newPost->user_id = $user->id;
        $newPost->updated_at = now();
        $newPost->created_at = now();
        $newPost->shared_from_user_id = $post->author->id;
        $newPost->save();

        return $newPost;
    }
}
