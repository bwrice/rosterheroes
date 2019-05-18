<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\PlayerStat::class, function (Faker $faker) {

    $amount = $faker->randomFloat(2,1,50);

    return [
        'player_game_log_id' => function() {
            return factory(\App\Domain\Models\PlayerGameLog::class)->create()->id;
        },
        'stat_type_id' => function() {
            return \App\Domain\Models\StatType::query()->inRandomOrder()->first()->id;
        },
        'amount' => $amount
    ];
});
