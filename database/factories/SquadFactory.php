<?php

use Faker\Generator as Faker;

$factory->define(\App\Squad::class, function (Faker $faker) {

    return [
        'user_id' => factory(\App\User::class)->create()->id,
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'province_id' => \App\Province::getStarting()->id,
        'squad_rank_id' => \App\SquadRank::getStarting()->id,
        'mobile_storage_rank_id' => \App\Squads\MobileStorage\MobileStorageRank::getStarting()->id,
        'salary' => \App\Squad::STARTING_SALARY,
        'gold' => \App\Squad::STARTING_GOLD,
        'favor' => \App\Squad::STARTING_FAVOR,
        'name' => $faker->company,
    ];
});
