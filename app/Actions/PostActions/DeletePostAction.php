<?php

namespace App\Actions\PostActions;

use App\Models\Post;

class DeletePostAction{
    public function execute(int $postId){

        if (Post::where('id', $postId)->delete())
            return true;
        else
            return false;

    }
}
