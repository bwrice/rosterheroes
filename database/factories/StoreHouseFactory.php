<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\StoreHouse::class, function (Faker $faker) {

    /** @var \App\Domain\Models\Squad $squad */
    $squad = factory(\App\Domain\Models\Squad::class)->create();
    /** @var \App\Domain\Models\HouseType $storeHouseType */
    $storeHouseType = \App\Domain\Models\HouseType::where('name', '=', \App\Domain\Models\HouseType::SHACK)->first();

    return [
        'squad_id' => $squad->id,
        'store_house_type_id' => $storeHouseType->id,
        'province_id' => $squad->province_id
    ];
});

$factory->afterCreatingState(\App\Domain\Models\StoreHouse::class, 'with-slots', function (\App\Domain\Models\StoreHouse $storeHouse, $faker) {
    $storeHouse->addSlots();
});
