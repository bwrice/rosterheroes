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

$factory->state(\App\Domain\Models\Item::class, 'single-handed', function ($faker) {

    $itemType = \App\Domain\Models\ItemType::query()->whereHas('itemBase', function (\Illuminate\Database\Eloquent\Builder $builder) {
        return $builder->whereIn('name', [
            \App\Domain\Models\ItemBase::DAGGER,
            \App\Domain\Models\ItemBase::SWORD,
            \App\Domain\Models\ItemBase::MACE,
            \App\Domain\Models\ItemBase::WAND,
            \App\Domain\Models\ItemBase::AXE,
            \App\Domain\Models\ItemBase::PSIONIC_ONE_HAND,
            \App\Domain\Models\ItemBase::THROWING_WEAPON,
        ]);
    })->inRandomOrder()->first();

    return [
        'item_type_id' => $itemType->id,
    ];
});

$factory->state(\App\Domain\Models\Item::class, 'two-handed', function ($faker) {

    $itemType = \App\Domain\Models\ItemType::query()->whereHas('itemBase', function (\Illuminate\Database\Eloquent\Builder $builder) {
        return $builder->whereIn('name', [
            \App\Domain\Models\ItemBase::CROSSBOW,
            \App\Domain\Models\ItemBase::TWO_HAND_SWORD,
            \App\Domain\Models\ItemBase::TWO_HAND_AXE,
            \App\Domain\Models\ItemBase::PSIONIC_TWO_HAND,
            \App\Domain\Models\ItemBase::BOW,
            \App\Domain\Models\ItemBase::STAFF,
            \App\Domain\Models\ItemBase::ORB,
            \App\Domain\Models\ItemBase::POLEARM,
        ]);
    })->inRandomOrder()->first();

    return [
        'item_type_id' => $itemType->id,
    ];
});

$factory->state(\App\Domain\Models\Item::class, 'shield', function ($faker) {

    $itemType = \App\Domain\Models\ItemType::query()->whereHas('itemBase', function (\Illuminate\Database\Eloquent\Builder $builder) {
        return $builder->whereIn('name', [
            \App\Domain\Models\ItemBase::SHIELD,
            \App\Domain\Models\ItemBase::PSIONIC_SHIELD,
        ]);
    })->inRandomOrder()->first();

    return [
        'item_type_id' => $itemType->id,
    ];
});

$factory->state(\App\Domain\Models\Item::class, 'head', function ($faker) {

    $itemType = \App\Domain\Models\ItemType::query()->whereHas('itemBase', function (\Illuminate\Database\Eloquent\Builder $builder) {
        return $builder->whereIn('name', [
            \App\Domain\Models\ItemBase::CAP,
            \App\Domain\Models\ItemBase::HELMET,
            \App\Domain\Models\ItemBase::CROWN,
        ]);
    })->inRandomOrder()->first();

    return [
        'item_type_id' => $itemType->id,
    ];
});
