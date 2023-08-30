<?php

namespace App\Actions\FriendActions;

use App\Models\User;
use App\Models\UserFriendRequest;
use App\Notifications\FriendRequestAccepted;
use Illuminate\Support\Facades\Auth;

class AcceptFriendRequest
{
    public function execute($friendRequestId): array
    {
        $friendRequest = UserFriendRequest::findOrFail($friendRequestId);

        if (Auth::user()->id !== $friendRequest->friend_id) {
            return ['status' => 'error', 'message' => 'You can only accept friend requests sent to you'];
        }

        $friend = User::findOrFail($friendRequest->user_id);
        Auth::user()->friends()->attach($friend->id);

        $friendRequest->delete();

        $friend->notify(new FriendRequestAccepted($friendRequest));
        return ['status' => 'success', 'message' => 'Friend Request Accepted Successfully'];
    }
}
