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

class CreateHeroAction
{
    /**
     * @param string $name
     * @param HeroClass $heroClass
     * @param HeroRace $heroRace
     * @param HeroRank $heroRank
     *
     * @return Hero|null
     */
    public function execute(string $name, HeroClass $heroClass, HeroRace $heroRace, HeroRank $heroRank): Hero
    {
        $heroUuid = Str::uuid();
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($heroUuid);
        $heroAggregate->recordThat(new HeroCreated(
            $name,
            $heroClass->id,
            $heroRace->id,
            $heroRank->id
        ));

        /*
         * Persist aggregate because we need an hero in the DB
         * for the rest of the creation process
         */
        $heroAggregate->persist();
        $hero = Hero::findUuid($heroUuid);

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
        return Hero::findUuid($heroUuid);
    }
}
