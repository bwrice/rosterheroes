<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Game::class, function (Faker $faker) {

    $week = factory(\App\Domain\Models\Week::class)->create();

    /** @var \App\Domain\Models\Team $homeTeam */
    $homeTeam = factory(\App\Domain\Models\Team::class)->create();

    /** @var \App\Domain\Models\Team $awayTeam */
    $awayTeam = factory(\App\Domain\Models\Team::class)->create([
        'league_id' => $homeTeam->league->id
    ]);

    return [
        'week_id' => $week->id,
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id,
        'starts_at' => $week->everything_locks_at->copy()->addHours(6),
        'external_id' => $faker->randomNumber()
    ];
});
