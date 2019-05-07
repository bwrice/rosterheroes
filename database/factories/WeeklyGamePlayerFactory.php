<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\WeeklyGamePlayer::class, function (Faker $faker) {


    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'uuid' => $uuid,
        'week_id' => function () {
            return factory(\App\Domain\Models\Week::class)->create()->id;
        },
        'player_id' => function () {
            return factory(\App\Domain\Models\Player::class)->create()->id;
        },
        'game_id' => function () {
            return factory(\App\Domain\Models\Game::class)->create()->id;
        },
        'salary' => \App\Domain\Models\WeeklyGamePlayer::MIN_SALARY
    ];
});
