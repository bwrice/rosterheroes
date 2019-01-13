<?php

use Faker\Generator as Faker;

$factory->define(\App\PlayerWeek::class, function (Faker $faker) {

    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();
    $player = \App\Player::query()->inRandomOrder()->first();
    $week = \App\Week::current();

    return [
        'uuid' => $uuid,
        'player_id' => $player->id,
        'week_id' => $week->id,
        'initial_salary' => \App\PlayerWeek::MIN_SALARY,
        'salary' => \App\PlayerWeek::MIN_SALARY
    ];
});
