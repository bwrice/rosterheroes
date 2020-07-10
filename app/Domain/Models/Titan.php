<?php

namespace App\Domain\Models;

use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\TitanCollection;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;


/**
 * Class Titan
 * @package App\Domain\Models
 *
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $level
 * @property int $enemy_type_id
 * @property int $combat_position_id
 *
 * @property AttackCollection $attacks
 * @property Collection $chestBlueprints
 */
class Titan extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withTimestamps();
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
