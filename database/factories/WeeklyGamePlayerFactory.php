<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\WeeklyGamePlayer::class, function (Faker $faker) {


    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'uuid' => $uuid,
        'week_id' => function () {
            return factory(\App\Domain\Models\Week::class)->create()->id;
        },
        'game_player_id' => function () {
            return factory(\App\Domain\Models\GamePlayer::class)->create()->id;
        },
        'salary' => \App\Domain\Models\WeeklyGamePlayer::MIN_SALARY
    ];
});
