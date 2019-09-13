<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Hero::class, function (Faker $faker) {

    $name = 'TestHero_' . random_int(1,999999999);
    $class = \App\Domain\Models\HeroClass::query()->inRandomOrder()->first();
    $heroRace = \App\Domain\Models\HeroRace::query()->inRandomOrder()->first();
    $rank = \App\Domain\Models\HeroRank::private();
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'name' => $name,
        'uuid' => $uuid,
        'hero_class_id' => $class->id,
        'hero_rank_id' => $rank->id,
        'hero_race_id' => $heroRace->id
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Hero::class, 'with-slots', function(\App\Domain\Models\Hero $hero, Faker $faker) {
    $heroSlotTypes = \App\Domain\Models\SlotType::heroTypes()->get();
    $heroSlotTypes->each(function (\App\Domain\Models\SlotType $slotType) use ($hero) {
        $hero->slots()->create([
            'slot_type_id' => $slotType->id,
        ]);
    });
});

$factory->afterCreatingState(\App\Domain\Models\Hero::class, 'with-measurables', function(\App\Domain\Models\Hero $hero, Faker $faker) {
    $measurableTypes = \App\Domain\Models\MeasurableType::heroTypes()->get();
    $measurableTypes->each(function (\App\Domain\Models\MeasurableType $measurableType) use ($hero) {
       $hero->measurables()->create([
           'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
           'measurable_type_id' => $measurableType->id,
           'amount_raised' => 0
       ]);
    });
});
