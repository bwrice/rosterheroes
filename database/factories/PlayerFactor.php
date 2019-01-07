<?php

use Faker\Generator as Faker;

$factory->define(\App\Player::class, function (Faker $faker) {

    return [
        'team_id' => function () {
            return \App\Team::inRandomOrder()->first()->id;
        },
        'name' => $faker->name
    ];
});
