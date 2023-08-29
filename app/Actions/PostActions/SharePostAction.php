<?php

namespace App\Actions\PostActions;

use App\Models\Post;

class DeletePostAction
{
    public function execute($userId, Post $post)
    {
        return 1;
    }
}
