<?php

namespace App;

use App\Domain\Models\ItemBlueprint;
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
}
