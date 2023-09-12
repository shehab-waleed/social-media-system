<?php

namespace App\Actions\FriendActions;

use App\Models\User;
use App\Models\UserFriendRequest;
use App\Notifications\FriendRequestSent;

class SendFriendRequest
{
    public function execute(User $user, User $friend): array
    {

        $userId = $user->id;
        if ($friend->id === $userId) {
            return ['status' => 'error', 'message' => 'Cannot send friend request to yourself'];
        }

        $existingRequest = UserFriendRequest::where('user_id', $userId)
            ->where('friend_id', $friend->id)
            ->first();

        if ($existingRequest) {
            return ['status' => 'error', 'message' => 'Friend request already sent to this user'];
        }
        $friendRequest = UserFriendRequest::create([
            'friend_id' => $friend->id,
            'user_id' => $userId,
            'status' => 'pending',
        ]);
        $friend->notify(new FriendRequestSent($friendRequest, 'pending'));

        return ['status' => 'success', 'message' => 'Friend request sent', 'data' => ['Friend Request Id' => $friendRequest->id]];

    }
}
