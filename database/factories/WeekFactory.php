<?php

use Faker\Generator as Faker;

$factory->define(\App\Week::class, function (Faker $faker) {

    $name = $faker->domainWord  . ' week of ' . $faker->safeColorName;

    $proposalsLockAt = \Carbon\Carbon::now('America/New_York')->next(3)->setTime(9,0,0)->setTimezone('UTC');

    return [
        'name' => $name,
        'proposals_scheduled_to_lock_at' => $proposalsLockAt->copy(),
        'diplomacy_scheduled_to_lock_at' => $proposalsLockAt->copy()->addDays(2),
        'everything_locks_at' => $proposalsLockAt->copy()->addDays(6)->addSecond(-1),
        'ends_at' => $proposalsLockAt->copy()->addDays(7)->addSecond(-1)
    ];
});
