<?php

namespace App;

use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseMinion
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property int $level
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property EnemyType $enemyType
 * @property CombatPosition $combatPosition
 *
 * @property Collection $chestBlueprints
 */
abstract class BaseMinion extends Model
{

    public function combatPosition()
    {
        return $this->belongsTo(CombatPosition::class);
    }

    public function enemyType()
    {
        return $this->belongsTo(EnemyType::class);
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }
}
