<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowingResource;
use App\Models\User;
use App\Notifications\FollowingNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Notification;

class FollowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Route::currentRouteName() == 'followed.index') {
            return ApiResponse::send(200, 'Followed users retrieved successfully .', Auth::user()->followed);
        } elseif (Route::currentRouteName() == 'followers.index') {
            return ApiResponse::send(200, 'Followers retrieved successfully .', FollowingResource::collection(Auth::user()->following));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($followedUserId)
    {
        $followedUser = User::findOrFail($followedUserId);

        if (! Auth::user()->following()->pluck('followed_id')->contains($followedUser->id)) {
            Auth::user()->following()->attach($followedUser->id);
            Notification::send($followedUser, new FollowingNotification(auth()->user()));
        }

        return ApiResponse::send(201, 'User followed successfully . ', null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $followedUser)
    {
        Auth::user()->following()->detach($followedUser->id);

        return ApiResponse::send(200, 'User un followed successfully . ', null);
    }
}
