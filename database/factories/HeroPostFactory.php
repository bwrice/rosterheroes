<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\HeroPost::class, function (Faker $faker) {

    $heroRace = \App\Domain\Models\HeroRace::query()->inRandomOrder()->first();
    return [
        'squad_id' => function() {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        },
        'hero_race_id' => $heroRace->id
    ];
});
