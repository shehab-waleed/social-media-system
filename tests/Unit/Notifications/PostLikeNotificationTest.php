<?php

namespace Tests\Unit\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notification;
use ReflectionClass;
use Tests\TestCase;

class PostLikeNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected Post $post;
    protected User $user;
    protected PostLikeNotification $notification;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
        $this->notification = new PostLikeNotification($this->post->id, $this->user);
    }

    public function test_it_send_the_proper_notification_message(){
        $this->assertEquals($this->notification->toArray($this->user) , [
            'post_id' => $this->post->id,
            'post_author_id' => $this->post->author->id,
            'message' => ucwords($this->user->first_name).', Liked your post !'
        ]);
    }

    public function test_it_send_notification_via_the_proper_driver(){
        $this->assertEquals($this->notification->via($this->user) , ['database']);
    }

    public function test_it_should_extend_notification_class(){
        $notificationReflection = new ReflectionClass(PostLikeNotification::class);

        $this->assertEquals(
            $notificationReflection->getParentClass()->getName(),
            Notification::class
        );
    }
}
