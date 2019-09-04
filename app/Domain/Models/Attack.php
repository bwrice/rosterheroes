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
 * @property float $speed_rating
 * @property float $base_damage_rating
 * @property float $damage_modifier_rating
 *
 * @property DamageType $damageType
 * @property TargetRange $targetRange
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

    public function targetRange()
    {
        return $this->belongsTo(TargetRange::class);
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
            $baseDamage = $hasAttacks->adjustBaseDamage( $baseDamage);
        }
        return (int) ceil($baseDamage);
    }

    public function getCombatSpeed(HasAttacks $hasAttacks = null): float
    {
        $baseSpeed = $this->speed_rating;
        $baseSpeed = $this->getBehavior()->adjustCombatSpeed($baseSpeed);
        if ($hasAttacks) {
            $baseSpeed = $hasAttacks->adjustCombatSpeed($baseSpeed);
        }
        return $baseSpeed;
    }
}
