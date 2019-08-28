<?php

use App\Domain\Models\ItemBlueprint;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define( ItemBlueprint::class, function(Faker $faker) {

    return [
        'item_name' => 'Blueprint Test Item',
        'enchantment_power' => 0,
        'attack_power' => 0
    ];
});
