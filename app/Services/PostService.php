<?php
namespace App\Services;

use App\Models\Post;
use App\Models\PostImages;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;


class PostService
{
    public function store(int $userId, string $title, string $body, ?array $images)
    {
        $post = Post::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
        ]);

        if (isset($images)) {
            foreach ($images as $image) {
                $imagePath = $image->store('post_images');

                PostImages::create([
                    'post_id' => $post->id,
                    'image' => $imagePath,
                ]);
            }
        }

        return $post;
    }


    public function destroy(Post $post): bool
    {

        if (!Auth()->user()->can('has-post', $post)) {
            abort(403, 'Your are not allowed to take this action');
        }

        foreach ($post->images as $element) {
            Storage::delete($element->image);
            $element->delete();
        }

        if ($post->delete())
            return true;
        else
            return false;
    }

}
