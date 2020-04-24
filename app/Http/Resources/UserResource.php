<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getKey(),
            'username' => $this->resource->getUsername(),
            'avatar' => $this->resource->getAvatar(),
            'email' => $this->resource->getEmail(),
            'created_at' => $this->resource->getCreatedAt(),
            'email_verified' => $this->resource->hasVerifiedEmail(),
        ];
    }
}
