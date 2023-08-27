<?php

namespace App\Actions\LikeActions\Post;

use App\Models\Post;

class UnlikePostAction
{
    public function execute($postLike, Post $post)
    {
        $postLike->delete();
        $post->likes_num > 0 ? $post->decrement('likes_num') : '';

        return true;
    }
}
