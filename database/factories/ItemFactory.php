<?php

use Faker\Generator as Faker;

$factory->define(\App\Item::class, function (Faker $faker) {

    /** @var \App\Items\ItemBases\ItemBase $itemBase */
    $itemBase = \App\Items\ItemBases\ItemBase::where('name','=', \App\Items\ItemBases\ItemBase::HELMET)->first();
    /** @var \App\ItemType $itemType */
    $itemType = $itemBase->itemTypes()->inRandomOrder()->first();

    return [
        'item_class_id' => \App\ItemClass::where('name', '=', \App\ItemClass::GENERIC)->first()->id,
        'item_type_id' => $itemType->id,
        'material_type_id' => $itemType->materialTypes()->inRandomOrder()->first()->id,
    ];
});
