<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Slot::class, function (Faker $faker) {

    $slotType = \App\Domain\Models\SlotType::heroTypes()->first();
    $hero = factory(\App\Domain\Models\Hero::class)->create();
    return [
        'slot_type_id' => $slotType->id,
        'has_slots_type' => \App\Domain\Models\Hero::RELATION_MORPH_MAP_KEY, // 'heroes'
        'has_slots_id' => $hero->id
    ];
});
