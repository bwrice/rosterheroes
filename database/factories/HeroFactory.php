<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Hero::class, function (Faker $faker) {

    $name = 'TestHero' . str_random(8);
    $race = \App\HeroRace::human();
    $class = \App\HeroClass::warrior();
    $rank = \App\HeroRank::private();

    return [
        'name' => $name,
        'squad_id' => factory(\App\Squad::class)->create()->id,
        'hero_race_id' => $race->id,
        'hero_class_id' => $class->id,
        'hero_rank_id' => $rank->id,
    ];
});

$factory->afterCreating(\App\Hero::class, function(\App\Hero $hero, Faker $faker) {
    $heroSlotTypes = \App\SlotType::heroTypes()->get();
    $heroSlotTypes->each(function (\App\SlotType $slotType) use ($hero) {
        $hero->slots()->create([
            'slot_type_id' => $slotType->id,
        ]);
    });
    $measurableTypes = \App\MeasurableType::heroTypes()->get();
    $measurableTypes->each(function (\App\MeasurableType $measurableType) use($hero) {
       $hero->measurables()->create([
           'measurable_type_id' => $measurableType->id
       ]);
    });
});
