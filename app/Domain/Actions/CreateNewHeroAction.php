<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/16/19
 * Time: 1:12 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Aggregates\MeasurableAggregate;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;
use App\StorableEvents\HeroCreated;
use Illuminate\Support\Str;

class CreateNewHeroAction
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var HeroClass
     */
    private $heroClass;
    /**
     * @var HeroRace
     */
    private $heroRace;
    /**
     * @var HeroRank
     */
    private $heroRank;

    public function __construct(string $name, HeroClass $heroClass, HeroRace $heroRace, HeroRank $heroRank)
    {
        $this->name = $name;
        $this->heroClass = $heroClass;
        $this->heroRace = $heroRace;
        $this->heroRank = $heroRank;
    }

    public function __invoke(): Hero
    {
        $heroUuid = Str::uuid();
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($heroUuid);
        $heroAggregate->recordThat(new HeroCreated(
            $this->name,
            $this->heroClass->id,
            $this->heroRace->id,
            $this->heroRank->id
        ));

        /*
         * Persist aggregate because we need an hero in the DB
         * for the rest of the creation process
         */
        $heroAggregate->persist();
        $hero = Hero::uuid($heroUuid);

        MeasurableType::heroTypes()->each(function (MeasurableType $measurableType) use ($hero) {
            $measurableUuid = Str::uuid();
            /** @var MeasurableAggregate $measurableAggregate */
            $measurableAggregate = MeasurableAggregate::retrieve($measurableUuid);
            $measurableAggregate->createMeasurable($measurableType->id, Hero::RELATION_MORPH_MAP_KEY, $hero->id, 0)
                ->persist();
        });

        SlotType::heroTypes()->each(function (SlotType $slotType) use ($heroAggregate) {
            $heroAggregate->createHeroSlot($slotType->id);
        });

        // Persist slots
        $heroAggregate->persist();
        return Hero::uuid($heroUuid);
    }

    /**
     * @return HeroClass
     */
    public function getHeroClass(): HeroClass
    {
        return $this->heroClass;
    }

    /**
     * @return HeroRace
     */
    public function getHeroRace(): HeroRace
    {
        return $this->heroRace;
    }

    /**
     * @return HeroRank
     */
    public function getHeroRank(): HeroRank
    {
        return $this->heroRank;
    }
}