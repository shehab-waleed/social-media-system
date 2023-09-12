<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use Illuminate\Http\JsonResponse;

class FriendController extends Controller
{
    public function index()
    {
        $friends = auth()->user()->friends;

        if ($friends->isEmpty()) {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'No friends found', []);
        }

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Friends retrieved successfully', FriendResource::collection($friends));
    }

    public function destroy($friendId)
    {
        $friend = auth()->user()->friends()->find($friendId);

        if (! $friend) {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'Friend not found');
        }

        auth()->user()->friends()->detach($friend->id);

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Friend deleted successfully');
    }
}
