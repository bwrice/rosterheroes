<?php

namespace App\Http\Resources;

use App\Domain\Models\Squad;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LocalSquadResource
 * @package App\Http\Resources
 *
 * @mixin Squad
 */
class LocalSquadResource extends JsonResource
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
            'level' => $this->level(),
            'localHeroes' => LocalHeroResource::collection($this->heroes)
        ];
    }
}
