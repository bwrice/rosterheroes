<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Week::class, function (Faker $faker) {

    /** @var \App\Domain\Models\Week $weekForNow */
    $weekForNow = \App\Domain\Models\Week::makeForNow();

    return [
        'uuid' => Str::uuid(),
        'proposals_scheduled_to_lock_at' => $weekForNow->proposals_scheduled_to_lock_at,
        'diplomacy_scheduled_to_lock_at' => $weekForNow->diplomacy_scheduled_to_lock_at,
        'everything_locks_at' => $weekForNow->adventuring_locks_at,
        'ends_at' => $weekForNow->ends_at
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Week::class, 'adventuring-open', function(\App\Domain\Models\Week $week, Faker $faker) {
    $week->adventuring_locks_at = \Illuminate\Support\Facades\Date::now()->addHour();
    $week->save();
});

$factory->afterCreatingState(\App\Domain\Models\Week::class, 'adventuring-closed', function(\App\Domain\Models\Week $week, Faker $faker) {
    $week->adventuring_locks_at = \Illuminate\Support\Facades\Date::now()->subHour();
    $week->save();
});

$factory->afterCreatingState(\App\Domain\Models\Week::class, 'as-current', function(\App\Domain\Models\Week $week, Faker $faker) {
    \App\Domain\Models\Week::setTestCurrent($week);
});
