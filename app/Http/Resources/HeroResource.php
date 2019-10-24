<?php

namespace App\Http\Resources;

use App\Domain\Models\Hero;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BarracksHeroResource
 * @package App\Http\Resources
 *
 * @mixin Hero
 */
class HeroResource extends JsonResource
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
            'heroClassID' => $this->hero_class_id,
            'heroRaceID' => $this->hero_race_id,
            'combatPositionID' => $this->combat_position_id,
            'playerSpirit' => new PlayerSpiritResource($this->playerSpirit),
            'measurables' => MeasurableResource::collection($this->measurables),
            'slots' => SlotResource::collection($this->slots)->collection->each(function (SlotResource $slotResource) {
                $slotResource->setUsesItems($this->resource);
            }),
            'spells' => SpellResource::collection($this->spells),
            'spellPower' => $this->getSpellPower()
        ];
    }
}
