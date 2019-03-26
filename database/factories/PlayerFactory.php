<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Players\Player::class, function (Faker $faker) {

    return [
        'team_id' => function () {
            return \App\Domain\Teams\Team::inRandomOrder()->first()->id;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'external_id' => $faker->randomNumber()
    ];
});
