<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Post Id' => $this->post->id,
            'Author' => $this->author->first_name.' '.$this->author->last_name,
            'Content' => $this->body,
            'Comment\'s Parent Id' => $this->parent_id ? intval($this->parent_id) : null,
        ];
    }
}
