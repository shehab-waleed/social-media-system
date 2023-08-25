<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\UserFriendRequest;
use App\Models\User;
use App\Notifications\FriendRequestSent;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function send (Request $request)
    {
        $request->validate([
            'friend_id' => ['required', "exists:users,id"],
        ]);
        $friend = User::findOrFail($request->friend_id);
        $friendRequest  =  UserFriendRequest::create([
            'friend_id' =>$friend->friend_id,
            'sender_id' =>auth()->user()->id,
            'status' => 'pending',
        ]);
        $friend->notify(new FriendRequestSent($friendRequest,'pending'));
        return ApiResponse::send('200','Friend request sent' ,NULL);
    }

}
