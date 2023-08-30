<?php

namespace App\Actions\FriendActions;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Models\UserFriendRequest;
use App\Notifications\FriendRequestSent;
use Illuminate\Support\Facades\Auth;

class SendFriendRequest
{
    public function execute(User $friend):array
    {

        $user =  Auth::user()->id;
        if ($friend->id ==$user) {
            return ['status' => 'error', 'message' => 'Cannot send friend request to yourself'];
        }

        $existingRequest = UserFriendRequest::where('user_id', Auth::user()->id)
            ->where('friend_id', $friend)
            ->first();

        if ($existingRequest) {
            return ['status' => 'error', 'message' => 'Friend request already sent to this user'];
        }
        $friendRequest = UserFriendRequest::create([
            'friend_id' => $friend->id,
            'user_id' => $user,
            'status' => 'pending',
        ]);
        $friend->notify(new FriendRequestSent($friendRequest, 'pending'));
        return ['status' => 'success', 'message' => 'Friend request sent','data'=>['Friend Request Id' => $friendRequest->id]];

    }


}
