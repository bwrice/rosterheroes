<?php

use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(\App\Domain\Models\Campaign::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'squad_id' => function() {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        },
        'week_id' => function() {
            return factory(\App\Domain\Models\Week::class)->create()->id;
        },
        'continent_id' => function() {
            return \App\Domain\Models\Continent::query()->inRandomOrder()->first()->id;
        }
    ];
});
