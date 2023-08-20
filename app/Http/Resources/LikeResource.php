<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "$this->likedAt Id" => $this->likedAt == 'Post' ? $this->post_id : $this->comment_id,
            'User Id' => $this->user_id,
        ];
    }
}
