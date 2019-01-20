<?php

use Faker\Generator as Faker;

$factory->define(\App\GamePlayer::class, function (Faker $faker) {

    //How to make sure player and game relation makes sense
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    /** @var \App\Player $player */
    $player = factory(\App\Player::class)->create();
    /** @var \App\Team $homeTeam */
    $homeTeam = $player->team;
    /** @var \App\Team $awayTeam */
    $awayTeam = \App\Team::query()->whereHas('sport', function(\Illuminate\Database\Eloquent\Builder $query) use ($homeTeam) {
        $query->where('id', '=', $homeTeam->sport->id);
    })->where('id', '!=', $homeTeam->id)->first();

    $game = factory(\App\Game::class)->create([
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id
    ]);

    return [
        'uuid' => $uuid,
        'player_id' => $player->id,
        'game_id' => $game->id,
        'initial_salary' => \App\GamePlayer::MIN_SALARY,
        'salary' => \App\GamePlayer::MIN_SALARY
    ];
});
