<?php

namespace App\Domain\Models;

use App\ChestBlueprint;
use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\MinionCollection;
use App\Domain\Interfaces\HasAttacks;
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
class Minion extends Model implements HasAttacks
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
        $baseHealth = 200 + (50 * $level) + (2 * ($level**2));
        $healthRatingBonus = $this->getHealthRating()/100;
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getHealthModifierBonus();
        return (int) ceil($baseHealth * (1 + $healthRatingBonus + $enemyTypeBonus));
    }

    public function getProtection(): int
    {
        $level = $this->getLevel();
        $baseProtection = 20 + (4 * $level) + (.2 * ($level**2));
        $protectionRatingBonus = $this->getProtectionRating()/100;
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        return (int) ceil($baseProtection * (1 + $protectionRatingBonus + $enemyTypeBonus));
    }

    public function getBlockChance(): float
    {
        $baseBlockChance = $this->getBlockRating()/4;
        $levelModifier = 1 + $this->getLevel()/100;
        $enemyTypeModifier = 1 + $this->getEnemyTypeBehavior()->getProtectionModifierBonus();
        return min(40, $baseBlockChance * $levelModifier * $enemyTypeModifier);
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $baseDamageRatingBonus = $this->getBaseDamageRating()/100;
        $levelBonus = $this->getLevel()/10;
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getBaseDamageModifierBonus();
        return (int) ceil($baseDamage * (1 + $baseDamageRatingBonus + $levelBonus + $enemyTypeBonus));
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $speedRatingBonus = $this->getCombatSpeedRating()/100;
        $levelBonus = $this->getLevel()/200;
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getCombatSpeedModifierBonus();
        return $speed * (1 + $speedRatingBonus + $levelBonus + $enemyTypeBonus);
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        $damageMultiplierRatingBonus = $this->getDamageMultiplierRating()/100;
        $levelBonus = $this->getLevel()/100;
        $enemyTypeBonus = $this->getEnemyTypeBehavior()->getDamageMultiplierModifierBonus();
        return $damageMultiplier * (1 + $damageMultiplierRatingBonus + $levelBonus + $enemyTypeBonus);
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
