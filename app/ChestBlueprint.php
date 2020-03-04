<?php

namespace App;

use App\Domain\Collections\MinionCollection;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Minion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChestBlueprint
 * @package App
 *
 * @property int $id
 * @property int $grade
 * @property int $min_gold
 * @property int $max_gold
 *
 * @property Collection $itemBlueprints
 * @property MinionCollection $minions
 */
class ChestBlueprint extends Model
{
    protected $guarded = [];

    public function itemBlueprints()
    {
        return $this->belongsToMany(ItemBlueprint::class)->withPivot('chance')->withTimestamps();
    }

    public function chests()
    {
        return $this->hasMany(Chest::class);
    }

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withTimestamps();
    }
}
