<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasAttacks;
use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
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
    protected $hasAttacks;

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
            'attackerPosition' => (new CombatPositionResource($this->attackerPosition))->setAttacker(true),
            'targetPosition' => (new CombatPositionResource($this->targetPosition))->setAttacker(false),
            'damageType' => new DamageTypeResource($this->damageType),
            'targetPriority' => new TargetPriorityResource($this->targetPriority),
            'grade' => $this->grade,
            'base_damage' => $this->getBaseDamage($this->hasAttacks),
            'damage_multiplier' => $this->getDamageMultiplier($this->hasAttacks),
            'combat_speed' => $this->getCombatSpeed($this->hasAttacks)
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
