<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\MinionCollection;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\HasFantasyPoints;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Minion
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $level
 * @property int $base_damage_rating
 * @property int $damage_multiplier_rating
 * @property int $health_rating
 * @property int $protection_rating
 * @property int $combat_speed_rating
 * @property int $block_rating
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property EnemyType $enemyType
 *
 * @property AttackCollection $attacks
 */
class Minion extends Model implements HasAttacks, HasFantasyPoints
{
    use HasNameSlug;

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new MinionCollection($models);
    }

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
    }

    public function enemyType()
    {
        return $this->belongsTo(EnemyType::class);
    }

    protected function getEnemyTypeBehavior(): EnemyTypeBehavior
    {
        return $this->enemyType->getBehavior();
    }

    public function getStartingHealth(): int
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getHealthModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->health_rating * (1 + $enemyTypeBonus));
    }

    public function getProtection(): int
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->protection_rating * (1 + $enemyTypeBonus));
    }

    public function getBlockChance(): float
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        return ($this->block_rating/4) * (1 + $enemyTypeBonus + (sqrt($this->level)/25));
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getBaseDamageModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->base_damage_rating * (1 + $enemyTypeBonus));
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getCombatSpeedModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->combat_speed_rating * (1 + $enemyTypeBonus));
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getCombatSpeedModifierBonus();
        return (int) ceil(sqrt($this->level) * ($this->level/5) * $this->damage_multiplier_rating * (1 + $enemyTypeBonus));
    }

    public function adjustResourceCostAmount(float $amount): float
    {
        return 0;
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return 0;
    }

    public function getFantasyPoints(): float
    {
        return $this->level/5 + (sqrt($this->level) * 6);
    }
}
