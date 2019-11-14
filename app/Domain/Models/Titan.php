<?php

namespace App\Domain\Models;

use App\Domain\Collections\TitanCollection;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;


/**
 * Class Titan
 * @package App\Domain\Models
 *
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $base_level
 * @property int $base_damage_rating
 * @property int $damage_multiplier_rating
 * @property int $health_rating
 * @property int $protection_rating
 * @property int $combat_speed_rating
 * @property int $block_rating
 * @property int $enemy_type_id
 * @property int $combat_position_id
 */
class Titan extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class);
    }

    public function newCollection(array $models = [])
    {
        return new TitanCollection($models);
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
