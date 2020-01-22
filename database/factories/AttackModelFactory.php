<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Attack::class, function (Faker $faker) {
    return [
        'name' => 'Factory Attack',
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
        'config_path' => ''
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Attack::class, 'with-config', function (\App\Domain\Models\Attack $attack, Faker $faker) {
    $attack->setGrade(10)
        ->setSpeedRating(5)
        ->setBaseDamageRating(10)
        ->setDamageMultiplierRating(10)
        ->setFixedTargetCount(1)
        ->setResourceCosts([])
        ->setRequirements([]);
});
