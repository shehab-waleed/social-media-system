<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FriendRequestSent extends Notification
{
    use Queueable;

    protected $friendRequest;
    protected $status;

    public function __construct($friendRequest, $status)
    {
        $this->friendRequest = $friendRequest;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $senderName = $this->friendRequest->sender->first_name;
        $message = "You have received a friend request from {$senderName}.";

        return [
            'friend_request_id' => $this->friendRequest->id,
            'message' => $message,
            'status' => $this->status,
        ];
    }
}
