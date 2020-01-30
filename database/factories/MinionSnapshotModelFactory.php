<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\MinionSnapshot::class, function (Faker $faker) {
    return [
        'minion_id' => function () {
            return factory(\App\Domain\Models\Minion::class)->create()->id;
        },
        'week_id' => function () {
            return factory(\App\Domain\Models\Week::class)->create()->id;
        },
        'data' => []
    ];
});
