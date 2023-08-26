<?php
namespace App\Services;

use App\Models\Post;
use App\Models\PostImages;


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


}

