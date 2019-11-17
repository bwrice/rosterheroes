<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Quest::class, function (Faker $faker) {
    return [
        'level' => random_int(100,999),
        'percent' => 100,
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'name' => $faker->company . ' ' . $faker->city,
        'province_id' => \App\Domain\Models\Province::query()->inRandomOrder()->first()->id,
        'travel_type_id' => function() {
            return \App\Domain\Models\TravelType::query()->inRandomOrder()->first()->id;
        }
    ];
});
