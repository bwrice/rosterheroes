<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Quest::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'name' => $faker->company . ' ' . $faker->city,
        'province_id' => \App\Domain\Models\Province::query()->inRandomOrder()->first()->id
    ];
});
