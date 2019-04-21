<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\League::class, function (Faker $faker) {
    return [
        'sport_id' => factory(\App\Domain\Models\Sport::class)->create()->id,
        'name' => $faker->company,
        'abbreviation' => $faker->randomLetter . $faker->randomLetter . $faker->randomLetter
    ];
});
