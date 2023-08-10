<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentLikeRequest;
use App\Http\Resources\CommentLikeResource;
use App\Models\CommentLike;

class CommentLikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CommentLikeRequest $request)
    {
        $likeUnlikeResponse = $this->likeUnlike($request);
        return [
            'likeUnlike' => $likeUnlikeResponse->getData(),
        ];

    }

    public function likeUnlike($request)
    {
        // Get the authenticated user
        $user = $request->user();
        // Get the comment ID from the request
        $commentId = $request->comment_id;

        // Check if the user has already liked the post
        $existingLike = CommentLike::where('user_id', $user->id)
            ->where('comment_id', $commentId)
            ->first();

        if ($existingLike) {
            // User unlikes the comment
            $existingLike->delete();
            return ApiResponse::send(200, 'You unliked the comment', []);
        } else {
            // User likes the post
            $like = new CommentLike([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ]);

            if ($like->save()) {
                return ApiResponse::send(200, 'You liked the comment', new CommentLikeResource($like));
            }

            return ApiResponse::send(500, 'Some error occurred, please try again');
        }
    }


}

