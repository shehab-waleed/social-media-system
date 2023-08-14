<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostLikeNotification extends Notification
{
    use Queueable;

    protected $postId;
    /**
     * Create a new notification instance.
     */
    public function __construct($postId)
    {
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
        $post = Post::with('author')->find($this->postId);
        return [
            'post_id' => $post->id,
            'post_author_id' => $post->author->id,
            'message' => ucwords(auth()->user()->first_name) . ', Liked your post !',
        ];
    }
}
