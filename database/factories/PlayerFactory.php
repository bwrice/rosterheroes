<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Player::class, function (Faker $faker) {

    return [
        'team_id' => function () {
            return factory(\App\Domain\Models\Team::class)->create()->id;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'external_id' => $faker->randomNumber()
    ];
});
