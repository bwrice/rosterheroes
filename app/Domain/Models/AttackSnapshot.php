<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasAttackSnapshots;
use App\Domain\Models\Casts\CastResourceCosts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class AttackSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $attack_id
 * @property int $damage
 * @property float $combat_speed
 * @property string $name
 * @property int $attacker_position_id
 * @property int $target_position_id
 * @property int $damage_type_id
 * @property int $target_priority_id
 * @property int $tier
 * @property int $targets_count
 *
 * @property int $attacker_id
 * @property string $attacker_type
 *
 * @property Attack $attack
 * @property CombatPosition $attackerPosition
 * @property CombatPosition $targetPosition
 * @property DamageType $damageType
 * @property TargetPriority $targetPriority
 * @property HasAttackSnapshots $attacker
 *
 * @property Collection $resource_costs
 */
class AttackSnapshot extends Model
{
    protected $guarded = [];

    protected $casts = [
        'resource_costs' => CastResourceCosts::class
    ];

    public function attack()
    {
        return $this->belongsTo(Attack::class);
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

    public function attacker()
    {
        return $this->morphTo();
    }
}
