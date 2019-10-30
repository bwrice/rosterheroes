<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Stash::class, function (Faker $faker) {
    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'squad_id' => function () {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        },
        'province_id' => function () {
            return \App\Domain\Models\Province::query()->inRandomOrder()->first()->id;
        }
    ];
});
