<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Stash::class, function (Faker $faker) {
    return [
        'squad_id' => function () {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        },
        'province_id' => function () {
            return \App\Domain\Models\Province::inRandomOrder()->first()->id;
        }
    ];
});
