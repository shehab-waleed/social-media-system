<?php

namespace App\Http\Controllers\Api;

use App\Actions\PostActions\SharePostAction;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Notifications\SharingPostNotificaion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
