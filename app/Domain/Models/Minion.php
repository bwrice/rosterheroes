<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Minion
 * @package App\Domain\Models
 *
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $level
 * @property int $damage_rating
 * @property int $health_rating
 * @property int $protection_rating
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property EnemyType $enemyType
 */
class Minion extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
    }

    public function enemyType()
    {
        return $this->belongsTo(EnemyType::class);
    }

}
