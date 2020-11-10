<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroSnapshot;
use App\Domain\Models\ItemSnapshot;
use App\Domain\Models\MeasurableSnapshot;
use App\Domain\Models\MeasurableType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HeroSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin HeroSnapshot
 */
class HeroSnapshotResource extends JsonResource
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
            'combatPositionID' => $this->combat_position_id,
            'heroRaceID' => $this->hero->hero_race_id,
            'heroClassID' => $this->hero->hero_class_id,
            'health' => $this->getMeasurableSnapshot(MeasurableType::HEALTH)->final_amount,
            'stamina' => $this->getMeasurableSnapshot(MeasurableType::STAMINA)->final_amount,
            'mana' => $this->getMeasurableSnapshot(MeasurableType::MANA)->final_amount,
            'protection' => $this->protection,
            'blockChance' => $this->block_chance,
            'fantasyPower' => $this->fantasy_power,
            'playerSpirit' => new PlayerSpiritResource($this->playerSpirit),
            'attackSnapshots' => $this->attackSnapshotResources()
        ];
    }

    protected function attackSnapshotResources()
    {
        $attackSnapshots = collect();
        $this->itemSnapshots->each(function (ItemSnapshot $itemSnapshot) use (&$attackSnapshots) {
            $attackSnapshots = $attackSnapshots->merge($itemSnapshot->attackSnapshots);
        });
        return AttackSnapshotResource::collection($attackSnapshots);
    }
}
