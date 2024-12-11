<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLikeNotification extends Notification
{
    use Queueable;

    protected $postId;
    protected User $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($postId , User $user)
    {
        $this->postId = $postId;
        $this->user = $user;
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
        $post = Post::with('author')->find($this->postId);

        return [
            'post_id' => $post->id,
            'post_author_id' => $post->author->id,
            'message' => ucwords($this->user->first_name).', Liked your post !',
        ];
    }
}
