<?php

use Faker\Generator as Faker;

$factory->define(\App\StoreHouse::class, function (Faker $faker) {

    /** @var \App\Squad $squad */
    $squad = factory(\App\Squad::class)->create();
    /** @var \App\StoreHouseType $storeHouseType */
    $storeHouseType = \App\StoreHouseType::where('name', '=', \App\StoreHouseType::DEPOT)->first();

    return [
        'squad_id' => $squad->id,
        'store_house_type_id' => $storeHouseType->id,
        'province_id' => $squad->province_id
    ];
});
