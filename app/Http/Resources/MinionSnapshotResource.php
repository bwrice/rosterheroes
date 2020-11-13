<?php

namespace App\Http\Resources;

use App\Domain\Models\MinionSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MinionSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin MinionSnapshot
 */
class MinionSnapshotResource extends JsonResource
{
    protected ?int $count = null;
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
            'level' => $this->level,
            'combatPositionID' => $this->combat_position_id,
            'enemyTypeID' => $this->enemy_type_id,
            'startingHealth' => $this->starting_health,
            'startingStamina' => $this->starting_stamina,
            'startingMana' => $this->starting_mana,
            'protection' => $this->protection,
            'blockChance' => $this->block_chance,
            'fantasyPower' => $this->fantasy_power,
            'attackSnapshots' => AttackSnapshotResource::collection($this->attackSnapshots)
        ];
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count)
    {
        $this->count = $count;
        return $this;
    }
}
