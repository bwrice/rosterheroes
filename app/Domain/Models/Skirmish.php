<?php

namespace App\Domain\Models;

use App\Domain\Collections\MinionCollection;
use App\Domain\Collections\SkirmishCollection;
use App\Domain\Models\Quest;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Skirmish
 * @package App
 *
 * @property int $quest_id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 *
 * @property Quest $quest
 *
 * @property MinionCollection $minions
 */
class Skirmish extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new SkirmishCollection($models);
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
