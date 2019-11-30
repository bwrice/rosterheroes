<?php

use App\Domain\Models\Week;
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
    Week::setTestCurrent($week);
});
