<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BarracksHeroResource
 * @package App\Http\Resources
 *
 * @mixin \App\Domain\Models\Hero
 */
class BarracksHeroResource extends JsonResource
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
            'name' => $this->name,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'playerSpirit' => new PlayerSpiritResource($this->playerSpirit),
            'heroRace' => new HeroRaceResource($this->heroRace),
            'heroClass' => new HeroClassResource($this->heroClass),
            'measurables' => MeasurableResource::collection($this->measurables),
            'slots' => SlotResource::collection($this->slots)
        ];
    }
}
