<?php

use Faker\Generator as Faker;

$factory->define(App\Quest::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'name' => $faker->title,
        'province_id' => \App\Province::query()->inRandomOrder()->first()->id
    ];
});
