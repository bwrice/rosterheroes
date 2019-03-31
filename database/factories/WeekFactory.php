<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Week::class, function (Faker $faker) {

    $name = ucwords($faker->domainWord)  . ' Week of ' . ucwords($faker->safeColorName);

    $start = \Carbon\Carbon::now()->next(3);

    $wednesday = $start->copy()->setTimezone('America/New_York');
    $offset = $wednesday->getOffset();
    $wednesday = $wednesday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

    $friday = $start->copy()->addDays(2)->setTimezone('America/New_York');
    $offset = $friday->getOffset();
    $friday = $friday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

    $sunday = $start->copy()->addDays(4)->setTimezone('America/New_York');
    $offset = $sunday->getOffset();
    $sunday = $sunday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

    $monday = $start->copy()->addDays(5)->setTimezone('America/New_York');
    $offset = $monday->getOffset();
    $monday = $monday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

    return [
        'name' => $name,
        'proposals_scheduled_to_lock_at' => $wednesday,
        'diplomacy_scheduled_to_lock_at' => $friday,
        'everything_locks_at' => $sunday,
        'ends_at' => $monday
    ];
});
