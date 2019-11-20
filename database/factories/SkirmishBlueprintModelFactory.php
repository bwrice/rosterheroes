<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\SkirmishBlueprint::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
    ];
});
