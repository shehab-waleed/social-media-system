<?php

namespace App\Actions\PostActions;

use App\Models\Post;
use App\Models\PostImages;

class CreatePostAction
{
    public function execute($userId, $allPostData)
    {
        $allPostData = collect($allPostData);
        $allPostData->put('user_id', $userId);
        $postImages = $allPostData->get('images');

        $post = Post::create($allPostData->except('images')->toArray());

        if ($postImages) {
            foreach ($postImages as $image) {
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
