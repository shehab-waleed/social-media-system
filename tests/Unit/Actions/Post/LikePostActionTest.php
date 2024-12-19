<?php

namespace Tests\Unit\Actions\Post;

use App\Actions\LikeActions\Post\LikePostAction;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use App\Notifications\PostLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikePostActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Post $post;

    public function setup(): void
    {
        parent::setup();
        \Notification::fake();

        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
    }

    public function test_it_increments_post_likes_count(){
        $this->assertEquals(0, $this->post->likes_count);

        $like = (new LikePostAction())->execute($this->post, $this->user);

        $this->post->refresh();
        $this->assertInstanceOf(Like::class, $like);
        $this->assertEquals(1, $this->post->likes_num);
        $this->assertDatabaseCount('likes', 1);
        $like = Like::first();
        $this->assertEquals($like->parent_type, Post::class);
        $this->assertEquals($like->parent_id, $this->post->id);
        $this->assertEquals($like->user_id, $this->user->id);
    }

    public function test_it_notify_the_post_author_when_the_post_is_liked(){
        (new LikePostAction())->execute($this->post, $this->user);

        \Notification::assertCount(1);
        \Notification::assertSentTo($this->post->author, PostLikeNotification::class);
    }
}
