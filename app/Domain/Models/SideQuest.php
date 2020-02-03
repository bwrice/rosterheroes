<?php

namespace App\Domain\Models;

use App\Domain\Collections\MinionCollection;
use App\Domain\Collections\SideQuestCollection;
use App\Domain\Models\Quest;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuest
 * @package App
 *
 * @property int $id
 * @property int $quest_id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 *
 * @property Quest $quest
 *
 * @property MinionCollection $minions
 */
class SideQuest extends Model
{
    protected $guarded = [];

    use HasUuid;

    public function newCollection(array $models = [])
    {
        return new SideQuestCollection($models);
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withPivot('count')->withTimestamps();
    }

    public function difficulty(): int
    {
        return (int) ceil($this->minions->sum(function (Minion $minion) {
            return ((($minion->level ** 1.4)/100) + $minion->level/20) * $minion->pivot->count;
        }));
    }
}
