<?php

/* @var $factory Factory */

use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Measurable::class, function (Faker $faker) {
    return [
        'uuid' => Str::uuid(),
        'amount_raised' => 0,
        'measurable_type_id' => function() {
            return MeasurableType::query()->inRandomOrder()->first()->id;
        },
        'hero_id' => function() {
            return factory(Hero::class)->create()->id;
        }
    ];
});
