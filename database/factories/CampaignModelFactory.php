<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Campaign::class, function (Faker $faker) {
    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'squad_id' => function () {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        },
        'continent_id' => function () {
            return \App\Domain\Models\Continent::query()->inRandomOrder()->first()->id;
        },
        'week_id' => function () {
            return \App\Domain\Models\Week::current()->id;
        },
    ];
});
