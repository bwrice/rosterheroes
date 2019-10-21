<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Domain\Models\Spell;
use Faker\Generator as Faker;

$factory->define(Spell::class, function (Faker $faker) {
    return [
        'name' => 'Test Spell ' . random_int(1, 9999999)
    ];
});
