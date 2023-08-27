<?php
namespace App\Actions\LikeActions;
use App\Models\Post;
use App\Models\PostLike;

class UnlikePostAction{
    public function execute($postLike , Post $post){
        $postLike->delete();
        $post->likes_num > 0 ? $post->decrement('likes_num') : '';
        return true;
    }
}
