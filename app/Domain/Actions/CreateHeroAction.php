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
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\StorableEvents\HeroCreated;
use Illuminate\Support\Str;

class CreateHeroAction
{
    /**
     * @param string $name
     * @param Squad $squad
     * @param HeroClass $heroClass
     * @param HeroRace $heroRace
     * @param HeroRank $heroRank
     * @return Hero
     */
    public function execute(string $name, Squad $squad, HeroClass $heroClass, HeroRace $heroRace, HeroRank $heroRank): Hero
    {
        $heroUuid = Str::uuid();
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($heroUuid);
        $heroAggregate->createHero(
            $name,
            $squad->id,
            $heroClass->id,
            $heroRace->id,
            $heroRank->id,
            $heroClass->getBehavior()->getStartingCombatPosition()->id
        );

        /*
         * Persist aggregate because we need an hero in the DB
         * for the rest of the creation process
         */
        $heroAggregate->persist();
        $hero = Hero::findUuid($heroUuid);

        MeasurableType::heroTypes()->each(function (MeasurableType $measurableType) use ($hero) {

            Measurable::query()->create([
                'uuid' => (string) Str::uuid(),
                'measurable_type_id' => $measurableType->id,
                'hero_id' => $hero->id,
                'amount_raised' => 0
            ]);
        });

        // Persist slots
        $heroAggregate->persist();
        return Hero::findUuid($heroUuid);
    }
}
