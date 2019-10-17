<?php

namespace App\Domain\Models;

use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\QueryBuilders\AttackQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Attack
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 * @property int $grade
 * @property int $fixed_target_count
 * @property int $attacker_position_id
 * @property int $target_position_id
 * @property float $speed_rating
 * @property float $base_damage_rating
 * @property float $damage_multiplier_rating
 * @property string $resource_costs
 *
 * @property DamageType $damageType
 * @property CombatPosition $attackerPosition
 * @property CombatPosition $targetPosition
 * @property TargetPriority $targetPriority
 *
 * @property ItemCollection $items
 */
class Attack extends Model
{
    public const BASIC_BLADE_ATTACK_NAME = 'Slash';
    public const BASIC_BOW_ATTACK_NAME = 'Arrow';
    public const BASIC_MAGIC_ATTACK_NAME = 'Magic Bolt';

    public const STARTER_SWORD_ATTACKS = [
        self::BASIC_BLADE_ATTACK_NAME
    ];
    public const STARTER_BOW_ATTACKS = [
        self::BASIC_BOW_ATTACK_NAME
    ];
    public const STARTER_STAFF_ATTACKS = [
        self::BASIC_MAGIC_ATTACK_NAME
    ];

    protected $guarded = [];

    /** @var HasAttacks|null */
    protected $hasAttacks;

    /**
     * @return BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function itemBases()
    {
        return $this->belongsToMany(ItemBase::class)->withTimestamps();
    }

    public function damageType()
    {
        return $this->belongsTo(DamageType::class);
    }

    public function attackerPosition()
    {
        return $this->belongsTo(CombatPosition::class, 'attacker_position_id');
    }

    public function targetPosition()
    {
        return $this->belongsTo(CombatPosition::class, 'target_position_id');
    }

    public function targetPriority()
    {
        return $this->belongsTo(TargetPriority::class);
    }

    public function newCollection(array $models = [])
    {
        return new AttackCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new AttackQueryBuilder($query);
    }

    public function getBaseDamage(): int
    {
        $baseDamage = 10 * sqrt($this->base_damage_rating);
        $attackerPositionBonus = $this->attackerPosition->getBehavior()->getBaseDamageBonus();
        $damageTypeBonus = $this->damageType->getBehavior()->getBaseDamageBonus($this->fixed_target_count);
        $baseDamage *= (1 + $attackerPositionBonus + $damageTypeBonus);
        if ($this->hasAttacks) {
            $baseDamage = $this->hasAttacks->adjustBaseDamage($baseDamage);
        }
        return (int) ceil($baseDamage);
    }

    public function getCombatSpeed(): float
    {
        $combatSpeed = $this->speed_rating;
        $attackerPositionBonus = $this->attackerPosition->getBehavior()->getCombatSpeedBonus();
        $damageTypeBonus = $this->damageType->getBehavior()->getCombatSpeedBonus($this->fixed_target_count);
        $combatSpeed *= (1 + $attackerPositionBonus + $damageTypeBonus);
        if ($this->hasAttacks) {
            $combatSpeed = $this->hasAttacks->adjustCombatSpeed($combatSpeed);
        }
        return $combatSpeed;
    }

    public function getDamageMultiplier(): float
    {
        $damageMultiplier = $this->damage_multiplier_rating**.25;
        $attackerPositionBonus = $this->attackerPosition->getBehavior()->getDamageMultiplierBonus();
        $damageTypeBonus = $this->damageType->getBehavior()->getDamageMultiplierBonus($this->fixed_target_count);
        $damageMultiplier *= (1 + $attackerPositionBonus + $damageTypeBonus);
        if ($this->hasAttacks) {
            $damageMultiplier = $this->hasAttacks->adjustDamageMultiplier($damageMultiplier);
        }
        return $damageMultiplier;
    }

    public function getResourceCosts()
    {
        $resourceCostsArray = json_decode($this->resource_costs, true);

        $resourceCosts = array_map(function ($resourceCostArray) {
            switch ($resourceCostArray['type']) {
                case ResourceCost::FIXED:
                    return new FixedResourceCost($resourceCostArray['resource'], $resourceCostArray['amount']);
                case ResourceCost::PERCENT_AVAILABLE:
                    return new PercentResourceCost($resourceCostArray['resource'], $resourceCostArray['percent']);
            }
            throw new \RuntimeException("Unknown type for Resource Cost: " . $resourceCostArray['type']);
        }, $resourceCostsArray);

        return new ResourceCostsCollection($resourceCosts);
    }

    /**
     * @param HasAttacks|null $hasAttacks
     * @return Attack
     */
    public function setHasAttacks(?HasAttacks $hasAttacks): Attack
    {
        $this->hasAttacks = $hasAttacks;
        return $this;
    }
}
