<?php

use Faker\Generator as Faker;

$factory->define(App\Campaign::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'squad_id' => function() {
            return factory(\App\Squad::class)->create()->id;
        },
        'week_id' => function() {
            return factory(\App\Weeks\Week::class)->create()->id;
        },
        'continent_id' => function() {
            return \App\Continent::query()->inRandomOrder()->first()->id;
        }
    ];
});
