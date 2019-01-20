<?php

use Faker\Generator as Faker;

$factory->define(\App\Heroes\HeroPosts\HeroPost::class, function (Faker $faker) {

    $heroRace = \App\HeroRace::query()->inRandomOrder()->first();
    return [
        'squad_id' => function() {
            return factory(\App\Squad::class)->create()->id;
        },
        'hero_race_id' => $heroRace->id
    ];
});
