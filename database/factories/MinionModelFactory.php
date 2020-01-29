<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Minion::class, function (Faker $faker) {
    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'name' => ucwords($faker->colorName . " " . $faker->word . " Monster"),
        'config_path' => '/Yaml/Minions/test_minion.yaml',
        'enemy_type_id' => function() {
            return \App\Domain\Models\EnemyType::query()->inRandomOrder()->first()->id;
        },
        'combat_position_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        }
    ];
});
