<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $postId;

    public function __construct($postId)
    {
        //
        $this->postId = $postId;
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
        $post = Post::find($this->postId);

        return [
            'post_id' => $post->id,
            'post_owner_id' => $post->author->id,
            'user_who_created_comment' => auth()->user()->id,
            'message' => ucwords(auth()->user()->first_name).' , Commented on your post.',
        ];
    }
}
