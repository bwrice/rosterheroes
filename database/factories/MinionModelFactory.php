<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Minion::class, function (Faker $faker) {
    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'name' => ucwords($faker->colorName . " " . $faker->word . " Monster"),
        'level' => random_int(1, 99),
        'health_rating' => random_int(10, 100),
        'base_damage_rating' => random_int(10, 100),
        'damage_multiplier_rating' => random_int(10, 100),
        'protection_rating' => random_int(10, 100),
        'combat_speed_rating' => random_int(10, 100),
        'block_rating' => random_int(10, 100),
        'enemy_type_id' => function() {
            return \App\Domain\Models\EnemyType::query()->inRandomOrder()->first()->id;
        },
        'combat_position_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        }
    ];
});
