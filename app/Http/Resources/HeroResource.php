<?php

namespace App\Http\Resources;

use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
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
        $this->resource->items->each(function (Item $item) {
            $item->setUsesItems($this->resource);
            $item->attacks->each(function (Attack $attack) use ($item) {
                $attack->setHasAttacks($item);
            });
        });

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
            'statMeasurableBonuses' => StatMeasurableBonusResource::collection($this->getStatMeasurableBonuses()),
            'protection' => $this->getProtection(),
            'blockChance' => round($this->getBlockChance(), 2),
            'damagePerMoment' => round($this->getDamagePerMoment(), 1),
            'staminaPerMoment' => round($this->staminaPerMoment(), 2),
            'momentsWithStamina' => $this->momentsWithStamina(),
            'manaPerMoment' => round($this->manaPerMoment(), 2),
            'momentsWithMana' => $this->momentsWithMana()
        ];
    }
}
