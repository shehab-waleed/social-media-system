<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'First Name' => $this->first_name,
            'Last Name' => $this->last_name,
            'Username' => $this->username,
            'Country' => $this->country,
            'Email' => $this->email,
            'photo' => $this->photo ?? null,
            'Token' => $this->token,
        ];
    }
}
