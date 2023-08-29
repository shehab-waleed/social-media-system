<?php

namespace App\Actions\PostActions;

use App\Models\Post;

class DeletePostAction{
    public function execute(Post $post){

        if ($post->delete())
            return true;
        else
            return false;

    }
}
