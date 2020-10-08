<?php

namespace App\Domain\Models;

use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\MinionCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\Morphable;
use App\Domain\Interfaces\RewardsChests;
use App\Domain\Traits\HasConfigAttributes;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Traits\HasUuid;
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
 * @property int $level
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property EnemyType $enemyType
 * @property CombatPosition $combatPosition
 *
 * @property AttackCollection $attacks
 *
 * @property Collection $chestBlueprints
 */
class Minion extends Model implements HasAttacks, RewardsChests
{
    public const RELATION_MORPH_MAP_KEY = 'minions';

    use HasConfigAttributes;
    use HasNameSlug;
    use HasUuid;

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

    public function combatPosition()
    {
        return $this->belongsTo(CombatPosition::class);
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    public function minionSnapshots()
    {
        return $this->hasMany(MinionSnapshot::class);
    }

    protected function getEnemyTypeBehavior(): EnemyTypeBehavior
    {
        return $this->enemyType->getBehavior();
    }

    public function getStartingHealth(): int
    {
        return $this->getEnemyTypeBehavior()->getStartingHealth($this->level, $this->combatPosition);
    }

    public function getStartingStamina(): int
    {
        return 999999;
    }

    public function getStartingMana(): int
    {
        return 999999;
    }

    public function getProtection(): int
    {
        return $this->getEnemyTypeBehavior()->getProtection($this->level, $this->combatPosition);
    }

    public function getBlockChance(): float
    {
        return $this->getEnemyTypeBehavior()->getBlockChancePercent($this->level, $this->combatPosition);
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $this->getEnemyTypeBehavior()->adjustBaseDamage($baseDamage, $this->level, $this->combatPosition);
    }

    public function adjustCombatSpeed(float $speed): float
    {
        return $this->getEnemyTypeBehavior()->adjustCombatSpeed($speed, $this->level, $this->combatPosition);
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        return $this->getEnemyTypeBehavior()->adjustDamageMultiplier($damageMultiplier, $this->level, $this->combatPosition);
    }

    public function adjustResourceCostAmount(float $amount): int
    {
        return 0;
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return 0;
    }

    public function getFantasyPoints(): float
    {
        return $this->level/4 + (sqrt($this->level) * 6);
    }

    public function getExperienceReward()
    {
        return (int) ceil(($this->level * 6) + $this->level**1.25);
    }

    public function getFavorReward()
    {
        return (int) ceil(sqrt($this->level)/4);
    }

    public function getMorphType(): string
    {
        return self::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }

    public function getChestSourceName(): string
    {
        return $this->name;
    }

    public function getChestSourceType(): string
    {
        return 'Minion';
    }

    public function adjustResourceCosts(ResourceCostsCollection $resourceCosts): ResourceCostsCollection
    {
        return $resourceCosts;
    }
}
