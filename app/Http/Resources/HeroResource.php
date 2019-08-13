<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Hero
 * @package App\Http\Resources
 *
 * @mixin \App\Domain\Models\Hero
 */
class HeroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     *
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'playerSpirit' => new PlayerSpiritResource($this->whenLoaded('playerSpirit')),
            'heroPost' => new HeroPostResource($this->whenLoaded('heroPost')),
            'heroRace' => new HeroRaceResource($this->whenLoaded('heroRace')),
            'heroClass' => new HeroClassResource($this->whenLoaded('heroClass')),
            'measurables' => MeasurableResource::collection($this->whenLoaded('measurables'))
        ];
    }
}
