<?php

namespace App\Domain\Models;

use App\Domain\Collections\MinionCollection;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestBlueprint
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 * @property MinionCollection $minions
 */
class SideQuestBlueprint extends Model
{
    use HasUniqueNames;

    protected $guarded = [];

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withPivot('count');
    }
}
