<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FriendRequestAccepted extends Notification
{
    use Queueable;
    protected $friendRequest;
    public function __construct($friendRequest)
    {
        $this->friendRequest =$friendRequest;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user who request friend id' => $this->friendRequest->receiver->friend_id,
            'message' => ucwords($this->friendRequest->receiver->first_name).' Accept you !',
        ];
    }
}
