<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SharingPostNotificaion extends Notification
{
    use Queueable;

    private Post $post;

    private User $userWhoShare;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post, User $userWhoShare)
    {
        $this->post = $post;
        $this->userWhoShare = $userWhoShare;
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
            'post_id' => $this->post->id,
            'user_who_share_id' => $this->userWhoShare->id,
            'message' => "{$this->userWhoShare->first_name} shares your post.",
        ];
    }
}
