<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Stash;
use App\HeroSnapshot;
use Illuminate\Support\Str;

class HeroSnapshotFactory
{
    /** @var SquadSnapshot|null */
    protected $squadSnapshot;
    /** @var int|null */
    protected $heroID;
    /** @var int|null */
    protected $combatPositionID;
    /** @var PlayerSpirit|null */
    protected $playerSpirit;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $squadSnapshot = $this->getSquadSnapshot();

        /** @var HeroSnapshot $heroSnapshot */
        $heroSnapshot = HeroSnapshot::query()->create(array_merge([
            'uuid' => Str::uuid(),
            'squad_snapshot_id' => $squadSnapshot->id,
            'hero_id' => $this->getHeroID($squadSnapshot->squad_id),
            'player_spirit_id' => $this->playerSpirit ? $this->playerSpirit->id : null,
            'combat_position_id' => $this->getCombatPositionID(),
            'protection' => rand(0, 1000),
            'block_chance' => round(rand(0, 2000)/100, 2),
            'fantasy_power' => $this->playerSpirit ? round(rand(500, 4000)/100, 2) : 0
        ], $extra));
        return $heroSnapshot;
    }

    protected function getSquadSnapshot()
    {
        if ($this->squadSnapshot) {
            return $this->squadSnapshot;
        }

        return SquadSnapshotFactory::new()->create();
    }

    protected function getHeroID(int $squadID)
    {
        if ($this->heroID) {
            return $this->heroID;
        }
        return HeroFactory::new()->withSquadID($squadID)->create()->id;
    }

    protected function getCombatPositionID()
    {
        if ($this->combatPositionID) {
            return $this->combatPositionID;
        }
        return CombatPosition::query()->inRandomOrder()->first()->id;
    }

}
