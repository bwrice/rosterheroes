<?php

use App\ItemBlueprint;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define( ItemBlueprint::class, function( Faker $faker ) {

    $itemType = \App\ItemType::where('name', 'Short Sword')->first();
    $genericClass = \App\ItemClass::where('name', \App\ItemClass::GENERIC )->first();

    return [
        'item_type_id' => $itemType->id,
        'item_class_id' => $genericClass->id,
        'item_name' => 'Blueprint Test Item',
        'enchantments_power' => 0,
        'attacks_power' => 0
    ];
});
