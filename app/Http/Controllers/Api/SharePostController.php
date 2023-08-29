<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\PostActions\SharePostAction;
use App\Helpers\ApiResponse;
use App\Http\Resources\PostResource;

class SharePostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $postId, SharePostAction $sharePostAction)
    {
        $newPost = $sharePostAction->execute(Auth::user()->id, Post::with('author')->findOrFail($postId));
        return ApiResponse::send(201, 'Post shared successfully.', new PostResource($newPost));
    }
}
