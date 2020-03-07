<?php

namespace App\Domain\Models;

use App\ChestBlueprint;
use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\MinionCollection;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\HasFantasyPoints;
use App\Domain\Traits\HasConfigAttributes;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Minion
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property EnemyType $enemyType
 *
 * @property AttackCollection $attacks
 *
 * @property Collection $chestBlueprints
 */
class Minion extends Model implements HasAttacks, HasFantasyPoints
{

    use HasConfigAttributes;
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

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withTimestamps();
    }

    protected function getEnemyTypeBehavior(): EnemyTypeBehavior
    {
        return $this->enemyType->getBehavior();
    }

    public function getStartingHealth(): int
    {
        $level = $this->getLevel();
        $startingAmount = (100 * $level) + (2 * ($level**2));
        $enemyTypeModifier = 1 + $this->getEnemyTypeBehavior()->getHealthModifierBonus();
        return (int) ceil($startingAmount * $enemyTypeModifier);
    }

    public function getProtection(): int
    {
        $level = $this->getLevel();
        $startingAmount = (10 * $level) + (.2 * ($level**2));
        $enemyModifier = 1 + $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        return (int) ceil($startingAmount + $enemyModifier);
    }

    public function getBlockChance(): float
    {
        $levelModifier = 1 + $this->getLevel()/200;
        $enemyTypeModifier = 1 + $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        $baseBlockChance = $this->getBlockRating()/4;
        return min(70, $baseBlockChance * $levelModifier * $enemyTypeModifier);
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $levelModifier = 1 + ($this->getLevel()/10);
        $enemyTypeModifier = 1 + $this->getEnemyTypeBehavior()->getBaseDamageModifierBonus();
        return (int) ceil($baseDamage * $levelModifier * $enemyTypeModifier);
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $levelModifier = 1 + $this->getLevel()/200;
        $enemyTypeModifier = 1 + $this->getEnemyTypeBehavior()->getCombatSpeedModifierBonus();
        return (int) ceil($speed * $levelModifier * $enemyTypeModifier);
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        $levelModifier = 1 + ($this->getLevel()/100);
        $enemyTypeModifier = 1 + $this->getEnemyTypeBehavior()->getDamageMultiplierModifierBonus();
        return (int) ceil($damageModifier * $levelModifier * $enemyTypeModifier);
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
        return $this->getLevel()/5 + (sqrt($this->getLevel()) * 6);
    }

    public function getLevel()
    {
        return $this->getConfigAttribute('level');
    }

    public function getBaseDamageRating()
    {
        return $this->getConfigAttribute('base_damage_rating');
    }

    public function getDamageMultiplierRating()
    {
        return $this->getConfigAttribute('damage_multiplier_rating');
    }

    public function getHealthRating()
    {
        return $this->getConfigAttribute('health_rating');
    }

    public function getProtectionRating()
    {
        return $this->getConfigAttribute('protection_rating');
    }

    public function getCombatSpeedRating()
    {
        return $this->getConfigAttribute('combat_speed_rating');
    }

    public function getBlockRating()
    {
        return $this->getConfigAttribute('block_rating');
    }

    public function getExperienceReward()
    {
        $level = $this->getLevel();
        return (int) ceil(($level * 10) + $level**2);
    }
}
