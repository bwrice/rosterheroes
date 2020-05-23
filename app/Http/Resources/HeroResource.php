<?php

namespace App\Http\Resources;

use App\Domain\Models\Hero;
use Illuminate\Http\Resources\Json\JsonResource;
use function foo\func;

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
            'measurables' => MeasurableResource::collection($this->measurables)->collection->each(function (MeasurableResource $measurableResource) {
                $measurableResource->resource->hero = $this->resource;
            }),
            'gearSlots' => GearSlotResource::collection($this->getGearSlots()),
            'spells' => SpellResource::collection($this->spells)->collection->each(function (SpellResource $spellResource) {
                $spellResource->resource->setSpellCaster($this->resource);
            }),
            'spellPower' => $this->getSpellPower(),
            'manaUsed' => $this->getManaUsed(),
            'statMeasurableBonuses' => StatMeasurableBonusResource::collection($this->getStatMeasurableBonuses())
        ];
    }
}
