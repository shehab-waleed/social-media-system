<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\PostActions\DeletePostAction;

class SharePostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $postId, DeletePostAction $sharePostAction)
    {
        $newPost = $sharePostAction->execute();
        dd($newPost);

        $post = Post::findOrFail($postId);

    }
}
