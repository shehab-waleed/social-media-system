<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentLoveNotification extends Notification
{
    use Queueable;

    protected $commentId;
    /**
     * Create a new notification instance.
     */
    public function __construct($commentId)
    {
        $this->commentId = $commentId;
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
        $comment = Comment::with('author', 'post.author')->find($this->commentId);

        return [
            'comment_id' => $comment->id,
            'post_id' => $comment->post->id,
            'message' => ucwords(auth()->user()->first_name) . ", Loved your comment on {$comment->post->author->first_name}'s post. "
        ];
    }
}
