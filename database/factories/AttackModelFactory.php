<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Attack::class, function (Faker $faker) {
    return [
        'name' => 'Factory Attack',
        'damage_type_id' => function() {
            return \App\Domain\Models\DamageType::query()->inRandomOrder()->first()->id;
        },
        'target_range_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        },
        'target_priority_id' => function() {
            return \App\Domain\Models\TargetPriority::query()->inRandomOrder()->first()->id;
        },
        'grade' => 50,
        'speed_rating' => 50,
        'base_damage_rating' => 50,
        'damage_modifier_rating' => 50,
        'resource_costs' => json_encode([]),
        'requirements' => json_encode([])
    ];
});
