<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Teams\Team::class, function (Faker $faker) {

    $league = \App\League::query()->inRandomOrder()->first();

    $location = $faker->city;


    return [
        'league_id' => $league->id,
        'location' => $location,
        'name' => $faker->colorName,
        'integration_id' => $faker->randomNumber()
    ];
});
