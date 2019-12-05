<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Player::class, function (Faker $faker) {

    return [
        'team_id' => function () {
            return factory(\App\Domain\Models\Team::class)->create()->id;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Player::class, 'with-positions', function(\App\Domain\Models\Player $player, Faker $faker) {
    $sport = $player->team->league->sport;
    $count = random_int(1, 3);
    $positions = \App\Domain\Models\Position::query()->where('sport_id', '=', $sport->id)->inRandomOrder()->take($count)->get();
    $player->positions()->saveMany($positions);
});
