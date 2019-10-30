<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Residence::class, function (Faker $faker) {

    /** @var \App\Domain\Models\Squad $squad */
    $squad = factory(\App\Domain\Models\Squad::class)->create();
    /** @var \App\Domain\Models\ResidenceType $residenceType */
    $residenceType = \App\Domain\Models\ResidenceType::query()->where('name', '=', \App\Domain\Models\ResidenceType::SHACK)->first();

    return [
        'squad_id' => $squad->id,
        'uuid' => \Illuminate\Support\Str::uuid(),
        'residence_type_id' => $residenceType->id,
        'province_id' => $squad->province_id
    ];
});
