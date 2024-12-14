<?php

namespace App\Actions\FollowingActions;

use App\Models\User;
use App\Notifications\FollowingNotification;
use Illuminate\Support\Facades\Notification;

class FollowUserAction
{
    public function execute(User $user, User $followedUser)
    {
        if (! $user->following()->pluck('followed_id')->contains($followedUser->id)) {
            $user->following()->attach($followedUser->id);
        }
    }
}
