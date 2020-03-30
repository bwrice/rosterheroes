<?php

namespace App\Domain\Models;

use App\ChestBlueprint;
use App\Domain\Collections\MinionCollection;
use App\Domain\Collections\SideQuestCollection;
use App\Domain\Models\Quest;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
 * @property Collection $chestBlueprints
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

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withTimestamps();
    }

    public function difficulty(): int
    {
        return (int) $this->floatDifficulty();
    }

    protected function floatDifficulty()
    {
        return ceil($this->minions->sum(function (Minion $minion) {
            return ((($minion->getLevel() ** 1.4)/100) + $minion->getLevel()/20) * $minion->pivot->count;
        }));
    }

    public function getExperienceReward()
    {
        $difficult = $this->difficulty();
        return (int) ceil($difficult * 100 + ($difficult**2.25));
    }

    public function getExperiencePerMoment()
    {
        return $this->floatDifficulty()/4;
    }
}
