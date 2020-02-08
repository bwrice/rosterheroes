<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Squad;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HeroFactory
{
    /** @var int */
    protected $squadID;

    /** @var int */
    protected $heroClassID;

    /** @var int */
    protected $heroRankID;

    /** @var int */
    protected $heroRaceID;

    /** @var int */
    protected $combatPositionID;

    /** @var SquadFactory */
    protected $squadFactory;

    /** @var Collection */
    protected $measurableFactories;

    /** @var Collection */
    protected $itemFactories;

    /** @var PlayerSpiritFactory|null */
    protected $playerSpiritFactory;

    protected function __construct()
    {
        $this->squadFactory = SquadFactory::new();
        $this->measurableFactories = collect();
        $this->itemFactories = collect();
    }

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Hero
     * @throws \Exception
     */
    public function create(array $extra = []): Hero
    {
        /** @var Hero $hero */
        $hero = Hero::query()->create(array_merge(
            [
                'squad_id' => $this->squadID ?: $this->squadFactory->create()->id,
                'name' => 'TestHero_' . random_int(1,999999999),
                'uuid' => (string) Str::uuid(),
                'hero_class_id' => $this->heroClassID ?: $this->getDefaultHeroClassID(),
                'hero_race_id' => $this->heroRaceID ?: $this->getDefaultHeroRaceID(),
                'hero_rank_id' => $this->heroRankID ?: $this->getDefaultHeroRank(),
                'combat_position_id' => $this->combatPositionID ?: $this->getDefaultCombatPositionID()
            ],
            $extra
        ));

        $this->measurableFactories->each(function (MeasurableFactory $measurableFactory) use ($hero) {
            $measurableFactory->forHero($hero)->create();
        });
        return $hero->fresh();
    }

    public function heroClass(string $heroClassName)
    {
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $heroClassName)->first();

        $clone = clone $this;
        $clone->heroClassID = $heroClass->id;
        return $clone;
    }

    protected function getDefaultHeroClassID()
    {
        return HeroClass::query()->inRandomOrder()->first()->id;
    }

    public function heroRace(string $heroRaceName)
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $heroRaceName)->first();

        $clone = clone $this;
        $clone->heroRaceID = $heroRace->id;
        return $clone;
    }

    protected function getDefaultHeroRaceID()
    {
        return HeroRace::query()->inRandomOrder()->first()->id;
    }

    public function heroRank(string $heroRankName)
    {
        /** @var HeroRank $heroRank */
        $heroRank = HeroRank::query()->where('name', '=', $heroRankName)->first();

        $clone = clone $this;
        $clone->heroRankID = $heroRank->id;
        return $clone;
    }

    protected function getDefaultHeroRank()
    {
        return HeroRank::private()->id;
    }

    public function combatPosition(string $combatPositionName)
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = CombatPosition::query()->where('name', '=', $combatPositionName)->first();

        $clone = clone $this;
        $clone->combatPositionID = $combatPosition->id;
        return $clone;
    }

    protected function getDefaultCombatPositionID()
    {
        return CombatPosition::query()->inRandomOrder()->first()->id;
    }

    public function forSquad(Squad $squad)
    {
        $clone = clone $this;
        $clone->squadID = $squad->id;
        return $clone;
    }

    public function squad(SquadFactory $squadFactory)
    {
        $clone = clone $this;
        $clone->squadFactory = $squadFactory;
        return $clone;
    }

    public function withMeasurables(Collection $measurableFactories = null)
    {
        $measurableFactories = $measurableFactories ?: collect();
        $mappedFactories = MeasurableType::all()->map(function (MeasurableType $measurableType) use ($measurableFactories) {
            $match = $measurableFactories->first(function (MeasurableFactory $factory) use ($measurableType) {
                return $factory->getMeasurableTypeID() === $measurableType->id;
            });
            return $match ?: MeasurableFactory::new()->forMeasurableType($measurableType);
        });

        $clone = clone $this;
        $clone->measurableFactories = $mappedFactories;
        return $clone;
    }

    public function withPlayerSpirit(PlayerSpiritFactory $playerSpiritFactory = null)
    {
        $clone = clone $this;
        $clone->playerSpiritFactory = $playerSpiritFactory ?: PlayerSpiritFactory::new();
        return $clone;
    }
}
