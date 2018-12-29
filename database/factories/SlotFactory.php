<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Slots\Slot::class, function (Faker $faker) {

    $slotType = \App\SlotType::heroTypes()->first();
    $hero = factory(\App\Hero::class)->create();
    return [
        'slot_type_id' => $slotType->id,
        'has_slots_type' => \App\Hero::RELATION_MORPH_MAP_KEY, // 'heroes'
        'has_slots_id' => $hero->id
    ];
});
