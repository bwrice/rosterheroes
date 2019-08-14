<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Measurable::class, function (Faker $faker) {
    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'amount_raised' => 0,
        'measurable_type_id' => function() {
            return \App\Domain\Models\MeasurableType::query()->inRandomOrder()->first()->id;
        },
        'has_measurables_type' => \App\Domain\Models\Hero::RELATION_MORPH_MAP_KEY,
        'has_measurables_id' => function() {
            return factory(\App\Domain\Models\Hero::class)->create()->id;
        }
    ];
});
