<?php

namespace App\Domain\Models;

use App\Domain\Models\ChestBlueprint;
use App\Domain\Collections\MinionCollection;
use App\Domain\Collections\SideQuestCollection;
use App\Domain\Interfaces\Morphable;
use App\Domain\Interfaces\RewardsChests;
use App\Domain\Models\Quest;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class SideQuest
 * @package App
 *
 * @property int $id
 * @property int $quest_id
 * @property string $uuid
 * @property string|null $name
 * @property string $slug
 *
 * @property Quest $quest
 * @property SideQuestBlueprint|null $sideQuestBlueprint
 *
 * @property MinionCollection $minions
 * @property Collection $chestBlueprints
 */
class SideQuest extends Model implements RewardsChests
{
    public const RELATION_MORPH_MAP_KEY = 'side-quests';

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
        return $this->belongsToMany(Minion::class)->withPivot(['count'])->withTimestamps();
    }

    public function chestBlueprints()
    {
        return $this->belongsToMany(ChestBlueprint::class)->withPivot(['chance', 'count'])->withTimestamps();
    }

    public function sideQuestBlueprint()
    {
        return $this->belongsTo(SideQuestBlueprint::class);
    }

    public function sideQuestSnapshots()
    {
        return $this->hasMany(SideQuestSnapshot::class);
    }

    public function difficulty(): int
    {
        return (int) $this->floatDifficulty();
    }

    protected function floatDifficulty()
    {
        return ceil($this->minions->sum(function (Minion $minion) {
            return ((($minion->level** 1.4)/100) + $minion->level/20) * $minion->pivot->count;
        }));
    }

    public function getExperienceReward()
    {
        $difficult = $this->difficulty();
        return (int) ceil($difficult * 75 + ($difficult**2));
    }

    public function getFavorReward()
    {
        return (int) ceil(2.5 * sqrt($this->difficulty()));
    }

    public function getExperiencePerMoment()
    {
        return $this->floatDifficulty()/10;
    }

    public function buildName()
    {
        if ($this->name) {
            return $this->name;
        }

        if ($this->minions->count() === 1) {
            /** @var Minion $firstMinion */
            $firstMinion = $this->minions->first();
            return Str::plural($firstMinion->name, $firstMinion->pivot->count);
        }

        $enemyTypes = $this->minions->map(function (Minion $minion) {
            return $minion->enemyType;
        })->unique('id');

        if ($enemyTypes->count() === 1) {
            /** @var EnemyType $enemyType */
            $enemyType = $enemyTypes->first();
            return ucwords($enemyType->getPluralName()) . ' (Mixed)';
        }

        return 'Minions (Mixed)';
    }

    public function getMorphType(): string
    {
        return self::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }

    public function getChestSourceName(): string
    {
        return $this->buildName();
    }

    public function getChestSourceType(): string
    {
        return 'Side Quest';
    }
}
