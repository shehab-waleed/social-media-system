<?php

namespace Tests\Unit\Jobs;

use App\Jobs\DeleteUserPosts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use Tests\TestCase;

class DeleteUserPostsJobTest extends TestCase
{
    Use RefreshDatabase;

    protected User $user;
    protected ReflectionClass $classReflection;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->classReflection = new ReflectionClass(DeleteUserPosts::class);
    }

    public function test_it_has_handle_method()
    {
        $this->assertTrue($this->classReflection->hasMethod('handle'));
    }

    public function test_it_runs_in_queue()
    {
        $this->assertTrue(
            in_array(
                ShouldQueue::class , $this->classReflection->getInterfaceNames()
            )
        );
    }

    public function test_it_deletes_the_user_posts()
    {
        Post::factory()->count(3)->create(['user_id' => $this->user->id]);

        $this->assertEquals(3, $this->user->posts()->count());

        $job = new DeleteUserPosts($this->user->id);

        $job->handle();

        $this->assertEquals(0 , $this->user->posts()->count());
    }

    public function test_it_deletes_the_provided_user_posts_only()
    {
        $anotherUser = User::factory()->create();

        Post::factory()->count(3)->create(['user_id' => $anotherUser->id]);
        Post::factory()->count(3)->create(['user_id' => $this->user->id]);

        $this->assertEquals(3, $this->user->posts()->count());
        $this->assertEquals(3, $anotherUser->posts()->count());

        $job = new DeleteUserPosts($this->user->id);
        $job->handle();

        $this->assertEquals(0 , $this->user->posts()->count());
        $this->assertEquals(3, $anotherUser->posts()->count());
    }
}
