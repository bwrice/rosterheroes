<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Stash;
use App\Domain\Models\HeroSnapshot;
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
    /** @var HeroFactory */
    protected $heroFactory;

    protected bool $withMeasurableSnapshots = false;

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

        if ($this->withMeasurableSnapshots) {
            MeasurableType::all()->each(function (MeasurableType $measurableType) use ($heroSnapshot) {
                $measurableFactory = MeasurableFactory::new()->forMeasurableType($measurableType);
                MeasurableSnapshotFactory::new()
                    ->withMeasurableFactory($measurableFactory)
                    ->withHeroSnapshotID($heroSnapshot->id)
                    ->create();
            });
        }
        return $heroSnapshot;
    }

    protected function getSquadSnapshot()
    {
        if ($this->squadSnapshot) {
            return $this->squadSnapshot;
        }

        return SquadSnapshotFactory::new()->create();
    }

    public function forSquadSnapshot(SquadSnapshot $squadSnapshot)
    {
        $clone = clone $this;
        $clone->squadSnapshot = $squadSnapshot;
        return $clone;
    }

    public function withHeroFactory(HeroFactory $heroFactory)
    {
        $clone = clone $this;
        $clone->heroFactory = $heroFactory;
        return $clone;
    }

    protected function getHeroID(int $squadID)
    {
        if ($this->heroID) {
            return $this->heroID;
        }
        $heroFactory = $this->heroFactory ?: HeroFactory::new();
        return $heroFactory->withSquadID($squadID)->create()->id;
    }

    protected function getCombatPositionID()
    {
        if ($this->combatPositionID) {
            return $this->combatPositionID;
        }
        return CombatPosition::query()->inRandomOrder()->first()->id;
    }

    public function withPlayerSpirit(PlayerSpirit $playerSpirit)
    {
        $clone = clone $this;
        $clone->playerSpirit = $playerSpirit;
        return $clone;
    }

    public function withMeasurableSnapshots()
    {
        $clone = clone $this;
        $clone->withMeasurableSnapshots = true;
        return $clone;
    }

}
