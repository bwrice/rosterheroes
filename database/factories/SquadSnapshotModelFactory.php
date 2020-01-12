<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\SquadSnapshot;
use Faker\Generator as Faker;

$factory->define(SquadSnapshot::class, function (Faker $faker) {
    return [
        'squad_id' => function () {
            return factory(Squad::class)->create()->id;
        },
        'week_id' => function () {
            return factory(Week::class)->create()->id;
        },
        'data' => []
    ];
});
