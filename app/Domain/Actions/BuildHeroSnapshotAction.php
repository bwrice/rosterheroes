<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Week;
use App\Exceptions\BuildHeroSnapshotException;
use App\Domain\Models\HeroSnapshot;
use App\Domain\Models\SquadSnapshot;

class BuildHeroSnapshotAction
{
    /** @var SquadSnapshot */
    protected $squadSnapshot;

    /** @var Hero */
    protected $hero;

    /** @var Week */
    protected $currentWeek;

    public function execute(SquadSnapshot $squadSnapshot, Hero $hero): HeroSnapshot
    {
        $this->squadSnapshot = $squadSnapshot;
        $this->hero = $hero->loadMissing(Hero::heroResourceRelations());
        $this->currentWeek = Week::current();
        $this->validateHero();
        $this->validateSpirit();
        $this->validateSquadSnapshot();

        /** @var HeroSnapshot $heroSnapshot */
        $heroSnapshot = HeroSnapshot::query()->create([
            'squad_snapshot_id' => $this->squadSnapshot->id,
            'hero_id' => $this->hero->id,
            'player_spirit_id' => $this->hero->player_spirit_id,
            'data' => []
        ]);

        return $heroSnapshot;
    }

    protected function validateHero()
    {
        if ($this->squadSnapshot->squad_id !== $this->hero->squad_id) {
            throw new BuildHeroSnapshotException($this->squadSnapshot, $this->hero,"Hero and SquadSnapshot do not belong to the same squad", BuildHeroSnapshotException::CODE_INVALID_HERO);
        }
    }

    protected function validateSpirit()
    {
        $playerSpirit = $this->hero->playerSpirit;
        if (! $playerSpirit) {
            throw new BuildHeroSnapshotException($this->squadSnapshot, $this->hero, "No player spirit", BuildHeroSnapshotException::CODE_INVALID_PLAYER_SPIRIT);
        }
        if ($playerSpirit->week_id !== $this->currentWeek->id) {
            throw new BuildHeroSnapshotException($this->squadSnapshot, $this->hero, "Player spirit not part of current week", BuildHeroSnapshotException::CODE_INVALID_PLAYER_SPIRIT);
        }
    }

    protected function validateSquadSnapshot()
    {
        if ($this->squadSnapshot->week_id !== $this->currentWeek->id) {
            throw new BuildHeroSnapshotException($this->squadSnapshot, $this->hero, "SquadSnapshot is not for the current week", BuildHeroSnapshotException::CODE_INVALID_SQUAD_SNAPSHOT);
        }
    }
}
