<?php

namespace App\Actions\FollowingActions;

use App\Models\User;

class UnfollowUserAction
{
    public function execute(User $user, int $followedUserId)
    {
        $user->following()->detach($followedUserId);
    }
}
