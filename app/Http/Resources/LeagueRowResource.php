<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeagueRowResource extends JsonResource
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
            'week' => $this->resource->week,
            'position' => $this->resource->position,
            'account_id' => $this->resource->account_id,
            'username' => $this->resource->username,
            'class' => $this->resource->class,
            'score' => $this->resource->score,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
