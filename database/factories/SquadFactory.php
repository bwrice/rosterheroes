<?php

use Faker\Generator as Faker;

$factory->define(\App\Squad::class, function (Faker $faker) {

    return [
        'user_id' => factory(\App\User::class)->create()->id,
        'province_id' => \App\Province::getStarting()->id,
        'squad_rank_id' => \App\SquadRank::getStarting()->id,
        'name' => $faker->company,
    ];
});
