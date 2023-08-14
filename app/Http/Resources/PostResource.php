<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'Id' => $this->id,
            'title' => $this->title,
            'Content' => $this->body,
            'User Id' => $this->user_id,
            'Author' => $this->author->first_name . ' ' . $this->author->last_name,
        ];
        if ($this->comments->count() > 0) {
            $data['Comments'] = CommentResource::collection($this->comments);
        }
        if ($this->images->count() > 0) {
            $data['Images Urls'] = $this->images->pluck('image');
        }

        return $data;
    }
}
