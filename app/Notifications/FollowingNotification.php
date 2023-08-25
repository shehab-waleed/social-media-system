<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FollowingNotification extends Notification
{
    use Queueable;

    protected $userWhoFollow;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $userWhoFollow)
    {
        $this->userWhoFollow = $userWhoFollow;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user who follow id' => $this->userWhoFollow->id,
            'message' => ucwords($this->userWhoFollow->first_name).' Follows you !',
        ];
    }
}
