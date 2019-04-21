<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Sport::class, function (Faker $faker) {
    return [
        'name' => $faker->word . 'ball'
    ];
});
