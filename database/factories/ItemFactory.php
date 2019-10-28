<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Item::class, function (Faker $faker) {

    /** @var \App\Domain\Models\ItemType $itemType */
    $itemType = \App\Domain\Models\ItemType::query()->inRandomOrder()->first();

    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'item_class_id' => \App\Domain\Models\ItemClass::query()->where('name', '=', \App\Domain\Models\ItemClass::GENERIC)->first()->id,
        'item_type_id' => $itemType->id,
        'material_id' => $itemType->materials()->inRandomOrder()->first()->id,
        'item_blueprint_id' => function () {
            return factory(\App\Domain\Models\ItemBlueprint::class)->create()->id;
        }
    ];
});
