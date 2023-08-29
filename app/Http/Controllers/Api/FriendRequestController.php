<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\UserFriendRequest;
use App\Models\User;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\FriendRequestSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    //! Refactor this Contorller
    public function send(Request $request)
    {
        $request->validate([
            'friend_id' => ['required'],
        ]);
        $friend = User::findOrFail($request->friend_id);

        //! Check if the user sent request to the same user or not

        $friendRequest = UserFriendRequest::create([
            'friend_id' => $friend->id,
            'user_id' => Auth::user()->id,
            'status' => 'pending',
        ]);
        $friend->notify(new FriendRequestSent($friendRequest, 'pending'));
        return ApiResponse::send('200', 'Friend request sent', ['Friend Request Id' => $friendRequest->id]);
    }

    public function accept(Request $request)
    {
        $request->validate([
            'request_id' => ['required', 'exists:user_friend_requests,id']
        ]);

        $friendRequest = UserFriendRequest::findOrFail($request->request_id);
        Auth::user()->friends()->attach([
            'friend_id' => $friendRequest->friend_id
        ]);
        $friend = User::find($friendRequest->friend_id);
        $friendRequest->delete();

        $friend->notify(new FriendRequestAccepted($friendRequest));
        return ApiResponse::send('200', 'Friend Request Accepted Successfully', []);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'friend_id' => ['required', 'exists:user_friend_requests,id']
        ]);
        $friendRequest = UserFriendRequest::findOrFail($request->friend_id);
        $friendRequest->delete();
        return ApiResponse::send('200', 'Friend Request rejected successfully', []);
    }

}
