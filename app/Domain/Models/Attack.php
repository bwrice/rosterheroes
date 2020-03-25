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

    // snake case to match YAML config attributes
    protected $grade;
    protected $fixed_target_count;
    protected $speed_rating;
    protected $base_damage_rating;
    protected $damage_multiplier_rating;
    protected $resource_costs;
    protected $requirements;

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

    public function getResourceCostCollection()
    {
        $resourceCostsArray = $this->getInitialResourceCosts();

        $resourceCosts = array_map(function ($resourceCostArray) {
            switch ($resourceCostArray['type']) {
                case ResourceCost::FIXED:
                    $amount = $resourceCostArray['amount'];
                    if ($this->hasAttacks) {
                        $amount = $this->hasAttacks->adjustResourceCostAmount($resourceCostArray['amount']);
                    }
                    return new FixedResourceCost($resourceCostArray['resource'], $amount);
                case ResourceCost::PERCENT_AVAILABLE:
                    $percent = $resourceCostArray['percent'];
                    if ($this->hasAttacks) {
                        $percent = $this->hasAttacks->adjustResourceCostPercent($resourceCostArray['percent']);
                    }
                    return new PercentResourceCost($resourceCostArray['resource'], $percent);
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

    public function getGrade()
    {
        return $this->getConfigAttribute('grade');
    }

    public function getFixedTargetCount()
    {
        return $this->getConfigAttribute('fixed_target_count');
    }

    public function getInitialSpeed()
    {
        return $this->damageType->getBehavior()->getInitialCombatSpeed($this->tier, $this->targets_count);
    }

    public function getInitialBaseDamage()
    {
        $this->damageType->getBehavior()->getInitialBaseDamage($this->tier, $this->targets_count);
    }

    public function getInitialDamageMultiplier()
    {
        return $this->damageType->getBehavior()->getInitialDamageMultiplier($this->tier, $this->targets_count);
    }

    public function getInitialResourceCosts()
    {
        if ($this->hasAttacks) {
            return $this->hasAttacks->getResourceCosts($this->tier, $this->damageType->getBehavior(), $this->targets_count);
        }
        return new ResourceCostsCollection();
    }

    public function getRequirements()
    {
        return $this->getConfigAttribute('requirements');
    }

    /**
     * @param int $grade
     * @return $this
     */
    public function setGrade(int $grade)
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @param int|null $fixedTargetCount
     * @return $this
     */
    public function setFixedTargetCount(?int $fixedTargetCount)
    {
        $this->fixed_target_count = $fixedTargetCount;
        return $this;
    }

    /**
     * @param float $speedRating
     * @return $this
     */
    public function setSpeedRating(float $speedRating)
    {
        $this->speed_rating = $speedRating;
        return $this;
    }

    /**
     * @param float $baseDamageRating
     * @return $this
     */
    public function setBaseDamageRating(float $baseDamageRating)
    {
        $this->base_damage_rating = $baseDamageRating;
        return $this;
    }

    /**
     * @param float $damageMultiplierRating
     * @return $this
     */
    public function setDamageMultiplierRating(float $damageMultiplierRating)
    {
        $this->damage_multiplier_rating = $damageMultiplierRating;
        return $this;
    }

    /**
     * @param array $resource_costs
     * @return $this
     */
    public function setResourceCosts(array $resource_costs)
    {
        $this->resource_costs = $resource_costs;
        return $this;
    }

    /**
     * @param array $requirements
     * @return $this
     */
    public function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;
        return $this;
    }

    public function getMaxTargetsCount()
    {
        return $this->damageType->getBehavior()->getMaxTargetCount($this->grade, $this->getFixedTargetCount());
    }
}
