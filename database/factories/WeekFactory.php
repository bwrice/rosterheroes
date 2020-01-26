<?php

use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Week::class, function (Faker $faker) {


    return [
        'uuid' => Str::uuid(),
        'adventuring_locks_at' => function() {
            return Week::computeAdventuringLocksAt();
        }
    ];
});

$factory->afterCreatingState(Week::class, 'adventuring-open', function(Week $week, Faker $faker) {
    $week->adventuring_locks_at = Date::now()->addHour();
    $week->save();
});

$factory->afterCreatingState(Week::class, 'adventuring-closed', function(Week $week, Faker $faker) {
    $week->adventuring_locks_at = Date::now()->subHour();
    $week->save();
});

$factory->afterCreatingState(Week::class, 'as-current', function(Week $week, Faker $faker) {
    $week->made_current_at = Date::now();
    $week->save();
    CurrentWeek::setTestCurrent($week);
});

$factory->afterCreatingState(Week::class, 'finalizing', function (Week $week, Faker $faker) {
    $now = Date::now();
    $finalizingStartsAt =  \App\Facades\WeekService::finalizingStartsAt($now);
    $diffHours = $now->hoursUntil($finalizingStartsAt);
    $week->adventuring_locks_at = $now->subHours($diffHours + 1);
    $week->save();
});
