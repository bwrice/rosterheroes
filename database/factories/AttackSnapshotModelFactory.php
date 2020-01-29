<?php

/** @var Factory $factory */

use App\AttackSnapshot;
use App\Domain\Models\Attack;
use App\Domain\Models\Week;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(AttackSnapshot::class, function (Faker $faker) {
    return [
        'attack_id' => function () {
            return factory(Attack::class)->create()->id;
        },
        'week_id' => function () {
            return factory(Week::class)->create()->id;
        },
        'data' => []
    ];
});
