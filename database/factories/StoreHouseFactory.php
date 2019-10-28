<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Residence::class, function (Faker $faker) {

    /** @var \App\Domain\Models\Squad $squad */
    $squad = factory(\App\Domain\Models\Squad::class)->create();
    /** @var \App\Domain\Models\ResidenceType $storeHouseType */
    $storeHouseType = \App\Domain\Models\ResidenceType::where('name', '=', \App\Domain\Models\ResidenceType::SHACK)->first();

    return [
        'squad_id' => $squad->id,
        'store_house_type_id' => $storeHouseType->id,
        'province_id' => $squad->province_id
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Residence::class, 'with-slots', function (\App\Domain\Models\Residence $storeHouse, $faker) {
    $storeHouse->addSlots();
});
