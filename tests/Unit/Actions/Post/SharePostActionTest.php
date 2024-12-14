<?php

namespace Tests\Unit\Actions\Post;

use App\Actions\PostActions\SharePostAction;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharePostActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Post $post;
    protected SharePostAction $sharePostAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
        $this->sharePostAction = new SharePostAction();
    }

    public function test_it_shares_the_user_post_with_new_author_successfully()
    {
        $this->assertEquals(0 , $this->post->num_of_shares);
        $this->assertNotEquals($this->post->user_id , $this->user->id);
        $this->assertDatabaseCount('posts', 1);

        $newPost = $this->sharePostAction->execute($this->user, $this->post);
        $this->post->refresh();

        $this->assertTrue($newPost instanceof Post);
        $this->assertDatabaseCount('posts', 2);
        $this->assertEquals([
            $this->post->num_of_shares,
            $newPost->shared_from_user_id,
            $newPost->user_id,
            $newPost->title,
            $newPost->body,
        ],[
            1,
            $this->post->author->id,
            $this->user->id,
            $this->post->title,
            $this->post->body,
        ]);
    }
}
