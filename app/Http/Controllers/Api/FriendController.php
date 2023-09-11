<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{


    public function show()
    {
        $user = Auth::user();
        $friends = $user->friends()
            ->select('users.id', 'first_name', 'photo')
            ->get();

        if ($friends->isEmpty()) {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'No friends found', []);
        }

        $formattedFriends = FriendResource::collection($friends);

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Friends retrieved successfully', $formattedFriends);
    }

    public function delete($friendId)
    {
        $friend = User::with('friends')->find($friendId);
        if (!$friend) {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'Friend not found', []);
        }
        $friend->delete();
        return ApiResponse::send(JsonResponse::HTTP_OK, 'Friend deleted successfully', []);
    }


}

