<?php

use Faker\Generator as Faker;

$factory->define(\App\Stash::class, function (Faker $faker) {
    return [
        'squad_id' => function () {
            return factory(\App\Squad::class)->create()->id;
        },
        'province_id' => function () {
            return \App\Province::inRandomOrder()->first()->id;
        }
    ];
});
