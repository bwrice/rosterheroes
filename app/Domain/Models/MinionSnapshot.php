<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MinionSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $week_id
 * @property int $minion_id
 * @property int $level
 * @property int $combat_position_id
 * @property int $enemy_type_id
 * @property int $starting_health
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
 */
class MinionSnapshot extends Model
{
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
}
