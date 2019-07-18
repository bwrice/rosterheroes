<?php

use App\Domain\Models\ItemBlueprint;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define( ItemBlueprint::class, function( Faker $faker ) {

    $itemType = \App\Domain\Models\ItemType::where('name', 'Short Sword')->first();
    $genericClass = \App\Domain\Models\ItemClass::where('name', \App\Domain\Models\ItemClass::GENERIC )->first();

    return [
        'item_type_id' => $itemType->id,
        'item_class_id' => $genericClass->id,
        'item_name' => 'Blueprint Test Item',
        'enchantment_power' => 0,
        'attack_power' => 0
    ];
});
