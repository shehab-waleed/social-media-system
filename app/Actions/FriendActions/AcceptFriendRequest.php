<?php

namespace App\Actions\FriendActions;

use App\Models\User;
use App\Models\UserFriendRequest;
use App\Notifications\FriendRequestAccepted;

class AcceptFriendRequest
{
    public function execute(User $user, UserFriendRequest $friendRequestId): array
    {

        if ($user->id !== $friendRequestId->friend_id) {
            return ['status' => 'error', 'message' => 'You can only accept friend requests sent to you'];
        }

        $friend = User::findOrFail($friendRequestId->user_id);
        $user->friends()->attach($friend->id);

        $friendRequestId->delete();

        $friend->notify(new FriendRequestAccepted($friendRequestId));

        return ['status' => 'success', 'message' => 'Friend Request Accepted Successfully'];
    }
}
