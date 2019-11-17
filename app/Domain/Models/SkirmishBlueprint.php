<?php

namespace App\Domain\Models;

use App\Domain\Collections\MinionCollection;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SkirmishBlueprint
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 * @property MinionCollection $minions
 */
class SkirmishBlueprint extends Model
{
    use HasNameSlug;
    use HasUniqueNames;

    protected $guarded = [];

    public function minions()
    {
        return $this->belongsToMany(Minion::class, 'minion_skirmish_blueprint', 'blueprint_id')
            ->withPivot('count');
    }
}
