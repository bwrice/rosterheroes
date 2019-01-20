<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Hero::class, function (Faker $faker) {

    $name = 'TestHero' . str_random(8);
    $class = \App\HeroClass::query()->inRandomOrder()->first();
    $rank = \App\HeroRank::private();
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'name' => $name,
        'uuid' => $uuid,
        'hero_class_id' => $class->id,
        'hero_rank_id' => $rank->id,
    ];
});

$factory->afterCreatingState(\App\Hero::class, 'with-slots', function(\App\Hero $hero, Faker $faker) {
    $heroSlotTypes = \App\SlotType::heroTypes()->get();
    $heroSlotTypes->each(function (\App\SlotType $slotType) use ($hero) {
        $hero->slots()->create([
            'slot_type_id' => $slotType->id,
        ]);
    });
});
$factory->afterCreatingState(\App\Hero::class, 'with-measurables', function(\App\Hero $hero, Faker $faker) {
    $measurableTypes = \App\MeasurableType::heroTypes()->get();
    $measurableTypes->each(function (\App\MeasurableType $measurableType) use($hero) {
       $hero->measurables()->create([
           'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
           'measurable_type_id' => $measurableType->id,
           'amount_raised' => 0
       ]);
    });
});
