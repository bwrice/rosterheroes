<?php

use Faker\Generator as Faker;

$factory->define(App\Game::class, function (Faker $faker) {

    $week = \App\Week::current();
    /** @var \App\Team $homeTeam */
    $homeTeam = \App\Team::query()->inRandomOrder()->first();
    /** @var \App\Team $awayTeam */
    $awayTeam = \App\Team::query()->whereHas('sport', function(\Illuminate\Database\Eloquent\Builder $query) use ($homeTeam) {
        $query->where('id', '=', $homeTeam->sport->id);
    })->where('id', '!=', $homeTeam->id)->first();

    return [
        'week_id' => $week->id,
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id,
        'starts_at' => $week->everything_locks_at->copy()->addHours(6)
    ];
});
