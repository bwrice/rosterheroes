<?php

use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\MeasurableType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Hero::class, function (Faker $faker) {

    return [
        'name' => 'TestHero_' . random_int(1,999999999),
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'squad_id' => function() {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        },
        'hero_class_id' => function() {
            return \App\Domain\Models\HeroClass::query()->inRandomOrder()->first()->id;
        },
        'hero_rank_id' => function() {
            return \App\Domain\Models\HeroRank::private()->id;
        },
        'hero_race_id' => function () {
            return \App\Domain\Models\HeroRace::query()->inRandomOrder()->first()->id;
        },
        'combat_position_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        }
    ];
});

$factory->afterCreatingState(Hero::class, 'with-measurables', function(Hero $hero, Faker $faker) {
    $measurableTypes = MeasurableType::heroTypes()->get();
    $measurableTypes->each(function (MeasurableType $measurableType) use ($hero) {
       $hero->measurables()->create([
           'uuid' => \Illuminate\Support\Str::uuid(),
           'measurable_type_id' => $measurableType->id,
           'amount_raised' => 0
       ]);
    });
});
