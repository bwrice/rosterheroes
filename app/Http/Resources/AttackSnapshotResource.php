<?php

namespace App\Http\Resources;

use App\Domain\Models\AttackSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AttackSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin AttackSnapshot
 */
class AttackSnapshotResource extends JsonResource
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
            'damage' => $this->damage,
            'combatSpeed' => round($this->combat_speed, 2),
            'name' => $this->name,
            'attackerPositionID' => $this->attacker_position_id,
            'targetPositionID' => $this->target_position_id,
            'damageTypeID' => $this->damage_type_id,
            'targetPriorityID' => $this->target_priority_id,
            'targetsCount' => $this->targets_count,
            'tier' => $this->tier,
            'resourceCosts' => $this->resource_costs
        ];
    }
}
