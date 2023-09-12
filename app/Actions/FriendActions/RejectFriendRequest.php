<?php

namespace App\Actions\FriendActions;

use App\Models\User;
use App\Models\UserFriendRequest;

class RejectFriendRequest
{
    public function execute(User $user, UserFriendRequest $friendId)
    {
        if ($user->id !== $friendId->friend_id) {
            return ['status' => 'error', 'message' => 'You can only accept friend requests sent to you'];
        }
        $friend = User::findorFail($friendId->friend_id);
        $friendId->delete();

        return ['status' => 'success', 'message' => 'Friend Request rejected successfully'];
    }
}
