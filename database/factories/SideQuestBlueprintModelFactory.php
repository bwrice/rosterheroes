<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\SideQuestBlueprint::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'uuid' => (string) \Illuminate\Support\Str::uuid()
    ];
});
