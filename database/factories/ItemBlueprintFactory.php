<?php

use App\Domain\Models\ItemBlueprint;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define( ItemBlueprint::class, function(Faker $faker) {

    return [
        'item_name' => 'Blueprint Test Item',
        'uuid' => (string) Str::uuid(),
        'enchantment_power' => null,
    ];
});
