<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Squad
 * @package App\Http\Resources
 *
 * @mixin \App\Squad
 */
class Squad extends JsonResource
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
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'name' => $this->name,
            'heroes' => Hero::collection($this->getHeroes())
        ];
    }
}
