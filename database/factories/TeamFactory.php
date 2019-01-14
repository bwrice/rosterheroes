<?php

use Faker\Generator as Faker;

$factory->define(\App\Team::class, function (Faker $faker) {

    $sport = \App\Sport::query()->inRandomOrder()->first();

    $location = $faker->city;


    return [
        'sport_id' => $sport->id
    ];
});
