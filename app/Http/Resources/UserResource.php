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
            'username' => $this->resource->getAttributeFromArray('username'),
            'avatar' => $this->resource->getAttributeFromArray('avatar'),
            'email' => $this->resource->getAttributeFromArray('email'),
            'created_at' => $this->resource->getAttributeFromArray('created_at'),
        ];
    }
}
