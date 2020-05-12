<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LeagueClassCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            's' => $this->resource[1] ?? null,
            'a' => $this->resource[2] ?? null,
            'b' => $this->resource[3] ?? null,
            'c' => $this->resource[4] ?? null,
            'd' => $this->resource[5] ?? null,
        ];
    }
}
