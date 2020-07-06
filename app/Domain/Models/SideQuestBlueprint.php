<?php

namespace App\Domain\Models;

use App\Domain\Models\ChestBlueprint;
use App\Domain\Collections\MinionCollection;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestBlueprint
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $name
 * @property string $reference_id
 * @property MinionCollection $minions
 * @property Collection $chestBlueprints
 */
class SideQuestBlueprint extends Model
{
    use HasUniqueNames;

    protected $guarded = [];

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withPivot('count')->withTimestamps();
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    public function sideQuests()
    {
        return $this->hasMany(SideQuest::class);
    }
}
