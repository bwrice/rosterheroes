<?php

use Faker\Generator as Faker;

$factory->define(\App\Campaigns\Quests\Quest::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'name' => $faker->company . ' ' . $faker->city,
        'province_id' => \App\Province::query()->inRandomOrder()->first()->id
    ];
});
