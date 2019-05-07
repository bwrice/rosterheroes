<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\PlayerGameLog::class, function (Faker $faker) {

    return [
        'game_id' => function() {
            return factory(\App\Domain\Models\Game::class)->create()->id;
        },
        'player_id' => function() {
            return factory(\App\Domain\Models\Player::class)->create()->id;
        },
        'team_id' => function() {
            return factory(\App\Domain\Models\Team::class)->create()->id;
        }
    ];
});
