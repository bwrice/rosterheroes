<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Game::class, function (Faker $faker) {

    $week = factory(\App\Domain\Models\Week::class)->create();
    /** @var \App\Domain\Models\Team $homeTeam */
    $homeTeam = \App\Domain\Models\Team::query()->inRandomOrder()->first();
    /** @var \App\Domain\Models\Team $awayTeam */
    $awayTeam = \App\Domain\Models\Team::query()->whereHas('league', function(\Illuminate\Database\Eloquent\Builder $query) use ($homeTeam) {
        $query->where('id', '=', $homeTeam->league->id);
    })->where('id', '!=', $homeTeam->id)->first();

    return [
        'week_id' => $week->id,
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id,
        'starts_at' => $week->everything_locks_at->copy()->addHours(6),
        'external_id' => $faker->randomNumber()
    ];
});
