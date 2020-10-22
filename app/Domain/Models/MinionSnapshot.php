<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasAttackSnapshots;
use App\Domain\Interfaces\RewardsChests;
use App\Domain\Models\Traits\UsesEnemyStatsCalculator;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * Class MinionSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $week_id
 * @property int $minion_id
 * @property string $name
 * @property int $level
 * @property int $combat_position_id
 * @property int $enemy_type_id
 * @property int $starting_health
 * @property int $starting_stamina
 * @property int $starting_mana
 * @property int $protection
 * @property float $block_chance
 * @property float $fantasy_power
 * @property int $experience_reward
 * @property int $favor_reward
 *
 * @property Week $week
 * @property Minion $minion
 * @property CombatPosition $combatPosition
 * @property EnemyType $enemyType
 *
 * @property EloquentCollection $attackSnapshots
 * @property EloquentCollection $chestBlueprints
 */
class MinionSnapshot extends Model implements HasAttackSnapshots, RewardsChests
{
    use UsesEnemyStatsCalculator, HasUuid;

    protected $guarded = [];

    public const RELATION_MORPH_MAP_KEY = 'minion-snapshots';

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function minion()
    {
        return $this->belongsTo(Minion::class);
    }

    public function combatPosition()
    {
        return $this->belongsTo(CombatPosition::class);
    }

    public function enemyType()
    {
        return $this->belongsTo(EnemyType::class);
    }

    public function attackSnapshots(): MorphMany
    {
        return $this->morphMany(AttackSnapshot::class, 'attacker');
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    public function getUuid(): string
    {
        return $this->uuid;
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
        return $this->minion->name;
    }

    public function getChestSourceType(): string
    {
        return 'Minion';
    }
}
