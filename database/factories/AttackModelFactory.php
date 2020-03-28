<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Domain\Models\Attack::class, function (Faker $faker) {
    return [
        'uuid' => Str::uuid(),
        'name' => 'Factory Attack ' . random_int(1, 999999),
        'attacker_position_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        },
        'target_position_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        },
        'damage_type_id' => function() {
            return \App\Domain\Models\DamageType::query()->inRandomOrder()->first()->id;
        },
        'target_priority_id' => function() {
            return \App\Domain\Models\TargetPriority::query()->inRandomOrder()->first()->id;
        },
        'tier' => 1
    ];
});
