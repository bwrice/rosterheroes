<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Week;
use App\Exceptions\BuildHeroSnapshotException;
use App\HeroSnapshot;
use App\SquadSnapshot;

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
        $this->validateSpirit();

        /** @var HeroSnapshot $heroSnapshot */
        $heroSnapshot = HeroSnapshot::query()->create([
            'squad_snapshot_id' => $this->squadSnapshot->id,
            'hero_id' => $this->hero->id,
            'data' => []
        ]);

        return $heroSnapshot;
    }

    protected function validateSpirit()
    {
        $playerSpirit = $this->hero->playerSpirit;
        if (! $playerSpirit) {
            throw new BuildHeroSnapshotException($this->hero, "No player spirit", BuildHeroSnapshotException::CODE_INVALID_PLAYER_SPIRIT);
        }
        if ($playerSpirit->week_id !== $this->currentWeek->id) {
            throw new BuildHeroSnapshotException($this->hero, "Player spirit not part of current week", BuildHeroSnapshotException::CODE_INVALID_PLAYER_SPIRIT);
        }
    }
}
