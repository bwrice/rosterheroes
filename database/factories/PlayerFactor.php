<?php

use Faker\Generator as Faker;

$factory->define(\App\Player::class, function (Faker $faker) {

    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'uuid' => $uuid,
        'team_id' => function () {
            return \App\Team::inRandomOrder()->first()->id;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName
    ];
});
