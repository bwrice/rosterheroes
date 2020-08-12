<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/16/19
 * Time: 1:12 PM
 */

namespace App\Domain\Actions;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Squad;
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
        /** @var Hero $hero */
        $hero = Hero::query()->create([
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'squad_id' => $squad->id,
            'hero_class_id' => $heroClass->id,
            'hero_race_id' => $heroRace->id,
            'hero_rank_id' => $heroRank->id,
            'combat_position_id' => $heroClass->getBehavior()->getStartingCombatPosition()->id
        ]);

        MeasurableType::heroTypes()->each(function (MeasurableType $measurableType) use ($hero) {

            Measurable::query()->create([
                'uuid' => (string) Str::uuid(),
                'measurable_type_id' => $measurableType->id,
                'hero_id' => $hero->id,
                'amount_raised' => 0
            ]);
        });

        return $hero->fresh();
    }
}
