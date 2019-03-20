<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Players\Player::class, function (Faker $faker) {

    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'uuid' => $uuid,
        'team_id' => function () {
            return \App\Domain\Teams\Team::inRandomOrder()->first()->id;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'integration_id' => $faker->randomNumber()
    ];
});
