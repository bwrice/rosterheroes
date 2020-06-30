<?php

namespace App\Domain\Models;

use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\QueryBuilders\AttackQueryBuilder;
use App\Domain\Traits\HasConfigAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Attack
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property int $fixed_target_count
 * @property int $attacker_position_id
 * @property int $target_position_id
 * @property int $target_priority_id
 * @property int $damage_type_id
 * @property int $tier
 * @property int|null $targets_count
 * @property string $config_path
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
    use HasConfigAttributes;

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

    public function itemTypes()
    {
        return $this->belongsToMany(ItemType::class)->withTimestamps();
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
        $baseDamage = $this->getInitialBaseDamage();
        if ($this->hasAttacks) {
            $baseDamage = $this->hasAttacks->adjustBaseDamage($baseDamage);
        }
        return (int) ceil($baseDamage);
    }

    public function getCombatSpeed(): float
    {
        $combatSpeed = $this->getInitialSpeed();
        if ($this->hasAttacks) {
            $combatSpeed = $this->hasAttacks->adjustCombatSpeed($combatSpeed);
        }
        return $combatSpeed;
    }

    public function getDamageMultiplier(): float
    {
        $damageMultiplier = $this->getInitialDamageMultiplier();
        if ($this->hasAttacks) {
            $damageMultiplier = $this->hasAttacks->adjustDamageMultiplier($damageMultiplier);
        }
        return $damageMultiplier;
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

    public function getInitialSpeed()
    {
        return $this->damageType->getBehavior()->getInitialCombatSpeed($this->tier, $this->targets_count);
    }

    public function getInitialBaseDamage()
    {
        return $this->damageType->getBehavior()->getInitialBaseDamage($this->tier, $this->targets_count);
    }

    public function getInitialDamageMultiplier()
    {
        return $this->damageType->getBehavior()->getInitialDamageMultiplier($this->tier, $this->targets_count);
    }

    public function getResourceCosts()
    {
        if ($this->hasAttacks) {
            return $this->hasAttacks->getResourceCosts($this->tier, $this->damageType->getBehavior(), $this->targets_count);
        }
        return new ResourceCostsCollection();
    }

    public function getRequirements()
    {
        // TODO
        return collect();
    }

    public function getMaxTargetsCount()
    {
        return $this->damageType->getBehavior()->getMaxTargetCount($this->tier, $this->targets_count);
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        return $this->damageType->getBehavior()->getDamagePerTarget($damage, $targetsCount);
    }

    public function getDamagePerMoment(float $fantasyPower)
    {
        /** @var CalculateCombatDamage $calculateDamage */
        $calculateDamage = app(CalculateCombatDamage::class);

        $damage = $calculateDamage->execute($this, $fantasyPower);
        $maxTargetsCount = $this->getMaxTargetsCount();
        $damagePerTarget = $this->getDamagePerTarget($damage, $maxTargetsCount);
        $totalDamage = $maxTargetsCount * $damagePerTarget;
        $speed = $this->getCombatSpeed();

        return $totalDamage * ($speed/100);
    }

    public function getStaminaPerMoment(): float
    {
        $staminaCost = $this->getResourceCosts()->sum(function (ResourceCost $resourceCost) {
            return $resourceCost->getExpectedStaminaCost();
        });
        return round($staminaCost * $this->getCombatSpeed()/100, 2);
    }

    public function getManaPerMoment(): float
    {
        $manaCost = $this->getResourceCosts()->sum(function (ResourceCost $resourceCost) {
            return $resourceCost->getExpectedManaCost();
        });
        return round($manaCost * $this->getCombatSpeed()/100, 2);
    }
}
