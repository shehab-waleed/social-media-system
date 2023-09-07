<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Actions\PostActions\SharePostAction;
use App\Notifications\SharingPostNotificaion;

class SharePostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $postId, SharePostAction $sharePostAction)
    {
        $originalPost = Post::with('author')->findOrFail($postId);
        $newPost = $sharePostAction->execute(Auth::user()->id, $originalPost);
        \Notification::send($originalPost->author, new SharingPostNotificaion($originalPost, Auth::user()));

        return ApiResponse::send(JsonResponse::HTTP_CREATED, 'Post shared successfully.', new PostResource($newPost));
    }
}
