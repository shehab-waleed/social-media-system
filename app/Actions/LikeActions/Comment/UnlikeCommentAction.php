<?php

namespace App\Actions\LikeActions\CommentActions;

use App\Models\Comment;

class UnlikeCommentAction
{
    public function execute($commentLike, Comment $comment)
    {
        $commentLike->delete();
        $comment->likes_num > 0 ? $comment->decrement('likes_num') : '';

        return true;
    }
}
