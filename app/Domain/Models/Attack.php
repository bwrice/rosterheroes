<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Attacks\AttackBehavior;
use App\Domain\Behaviors\Attacks\AttackBehaviorFactory;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasAttacks;
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
 * @property int $attacker_position_id
 * @property float $speed_rating
 * @property float $base_damage_rating
 * @property float $damage_modifier_rating
 *
 * @property DamageType $damageType
 * @property CombatPosition $attackerPosition
 * @property TargetPriority $targetPriority
 *
 * @property ItemCollection $items
 */
class Attack extends Model
{
    public const BASIC_BLADE_ATTACK_NAME = 'Cut';
    public const BASIC_BOW_ATTACK_NAME = 'Arrow';
    public const BASIC_MAGIC_ATTACK_NAME = 'Magic Bolt';

    public const START_SWORD_ATTACKS = [
        self::BASIC_BLADE_ATTACK_NAME
    ];
    public const STARTER_BOW_ATTACKS = [
        self::BASIC_BOW_ATTACK_NAME
    ];
    public const STARTER_STAFF_ATTACKS = [
        self::BASIC_MAGIC_ATTACK_NAME
    ];

    protected $guarded = [];

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

    public function getBehavior(): AttackBehavior
    {
        /** @var AttackBehaviorFactory $behaviorFactory */
        $behaviorFactory = app(AttackBehaviorFactory::class);
        return $behaviorFactory->getAttackBehavior($this);
    }

    public function getBaseDamage(HasAttacks $hasAttacks = null): int
    {
        $baseDamage = $this->base_damage_rating;
        $baseDamage = $this->getBehavior()->adjustBaseDamage($baseDamage);
        if ($hasAttacks) {
            $baseDamage = $hasAttacks->adjustBaseDamage($baseDamage);
        }
        return (int) ceil($baseDamage);
    }

    public function getCombatSpeed(HasAttacks $hasAttacks = null): float
    {
        $combatSpeed = $this->speed_rating;
        $combatSpeed = $this->getBehavior()->adjustCombatSpeed($combatSpeed);
        if ($hasAttacks) {
            $combatSpeed = $hasAttacks->adjustCombatSpeed($combatSpeed);
        }
        return $combatSpeed;
    }

    public function getDamageMultiplier(HasAttacks $hasAttacks = null): float
    {
        $damageModifier = 1;
        $damageModifier += $this->damage_modifier_rating**.25;
        $damageModifier = $this->getBehavior()->adjustDamageMultiplier($damageModifier);
        if ($hasAttacks) {
            $damageModifier = $hasAttacks->adjustDamageMultiplier($damageModifier);
        }
        return $damageModifier;
    }
}
