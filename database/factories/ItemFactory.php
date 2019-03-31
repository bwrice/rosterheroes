<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Item::class, function (Faker $faker) {

    /** @var \App\Domain\Models\ItemBase $itemBase */
    $itemBase = \App\Domain\Models\ItemBase::where('name','=', \App\Domain\Models\ItemBase::HELMET)->first();
    /** @var \App\Domain\Models\ItemType $itemType */
    $itemType = $itemBase->itemTypes()->inRandomOrder()->first();

    return [
        'item_class_id' => \App\Domain\Models\ItemClass::where('name', '=', \App\Domain\Models\ItemClass::GENERIC)->first()->id,
        'item_type_id' => $itemType->id,
        'material_type_id' => $itemType->materialTypes()->inRandomOrder()->first()->id,
    ];
});
