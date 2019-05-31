<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Week::class, function (Faker $faker) {

    $name = ucwords($faker->domainWord)  . ' Week of ' . ucwords($faker->safeColorName);

    /** @var \App\Domain\Models\Week $weekForNow */
    $weekForNow = \App\Domain\Models\Week::makeForNow();

    return [
        'name' => $name,
        'proposals_scheduled_to_lock_at' => $weekForNow->proposals_scheduled_to_lock_at,
        'diplomacy_scheduled_to_lock_at' => $weekForNow->diplomacy_scheduled_to_lock_at,
        'everything_locks_at' => $weekForNow->everything_locks_at,
        'ends_at' => $weekForNow->ends_at
    ];
});
