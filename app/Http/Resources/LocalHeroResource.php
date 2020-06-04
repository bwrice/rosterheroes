<?php

namespace App\Http\Resources;

use App\Domain\Models\Hero;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LocalHeroResource
 * @package App\Http\Resources
 *
 * @mixin Hero
 */
class LocalHeroResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'heroClassID' => $this->hero_class_id,
            'heroRaceID' => $this->hero_race_id
        ];
    }
}
