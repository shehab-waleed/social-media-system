<?php

namespace App\Http\Controllers\Api;

use App\Actions\FollowingActions\FollowUserAction;
use App\Actions\FollowingActions\UnfollowUserAction;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowingResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FollowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Route::currentRouteName() == 'followed.index') {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Followed users retrieved successfully .', Auth::user()->followed);
        } elseif (Route::currentRouteName() == 'followers.index') {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Followers retrieved successfully .', FollowingResource::collection(Auth::user()->following));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $followedUser, FollowUserAction $followUserAction)
    {
        $followUserAction->execute(Auth::user(), $followedUser);

        return ApiResponse::send(JsonResponse::HTTP_CREATED, 'User followed successfully . ', ['is_followed' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $followedUser, UnfollowUserAction $unfollowUserAction)
    {
        $unfollowUserAction->execute(Auth::user(), $followedUser->id);

        return ApiResponse::send(JsonResponse::HTTP_OK, 'User unfollowed successfully . ', ['is_followed' => false]);
    }
}
