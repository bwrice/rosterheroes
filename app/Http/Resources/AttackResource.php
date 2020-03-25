<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasAttacks;
use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
use App\Domain\Models\Item;
use App\Domain\Models\TargetPriority;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AttackResource
 * @package App\Http\Resources
 *
 * @mixin Attack
 */
class AttackResource extends JsonResource
{
    /** @var HasAttacks|null */
    public $hasAttacks;

    /** @var Attack */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->resource->setHasAttacks($this->hasAttacks);

        return [
            'name' => $this->name,
            'attackerPositionID' => $this->attacker_position_id,
            'targetPositionID' => $this->target_position_id,
            'damageType' => new DamageTypeResource($this->damageType),
            'targetPriority' => new TargetPriorityResource($this->targetPriority),
            'grade' => $this->getGrade(),
            'baseDamage' => $this->getBaseDamage(),
            'damageMultiplier' => round($this->getDamageMultiplier(), 2),
            'combatSpeed' => round($this->getCombatSpeed(), 2),
            'resourceCosts' => $this->getResourceCosts()
        ];
    }

    /**
     * @param HasAttacks $hasAttacks
     * @return AttackResource
     */
    public function setHasAttacks(HasAttacks $hasAttacks = null): AttackResource
    {
        $this->hasAttacks = $hasAttacks;
        return $this;
    }
}
