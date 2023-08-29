<?php

namespace App\Actions\PostActions;

use App\Models\Post;

class SharePostAction
{
    public function execute(int $userId, Post $post)
    {
        $newPost = $post->replicate();
        $newPost->user_id = $userId;
        $newPost->updated_at = now();
        $newPost->created_at = now();
        $newPost->shared_from = $post->author->id;
        $newPost->save();

        return $newPost;
    }
}
