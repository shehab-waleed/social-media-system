<?php

namespace App\Actions\FriendActions;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Models\UserFriendRequest;
use Illuminate\Support\Facades\Auth;

class RejectFriendRequest
{
    public function execute($friendId)
    {
        $friendRequest = UserFriendRequest::findOrFail($friendId);
        if (Auth::user()->id !== $friendRequest->friend_id) {
            return ['status' => 'error', 'message' => 'You can only accept friend requests sent to you'];
        }
        $friend = User::findorFail($friendRequest->friend_id);
        $friendRequest->delete();
        return ['status' => 'success', 'message' => 'Friend Request rejected successfully'];
    }

}
