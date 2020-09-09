<?php

namespace App;

use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AttackSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $attack_id
 * @property int $hero_snapshot_id
 * @property int $damage
 * @property string $name
 * @property int $attacker_position_id
 * @property int $target_position_id
 * @property int $damage_type_id
 * @property int $target_priority_id
 * @property int $tier
 * @property int $targets_count
 *
 * @property Attack $attack
 * @property HeroSnapshot $heroSnapshot
 * @property CombatPosition $attackerPosition
 * @property CombatPosition $targetPosition
 * @property DamageType $damageType
 * @property TargetPriority $targetPriority
 */
class AttackSnapshot extends Model
{
    protected $guarded = [];

    public function attack()
    {
        return $this->belongsTo(Attack::class);
    }

    public function heroSnapshot()
    {
        return $this->belongsTo(HeroSnapshot::class);
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
}
