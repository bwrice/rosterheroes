<?php

use Faker\Generator as Faker;

$factory->define(\App\GamePlayer::class, function (Faker $faker) {

    //How to make sure player and game relation makes sense
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    /** @var \App\Domain\Players\Player $player */
    $player = factory(\App\Domain\Players\Player::class)->create();
    /** @var \App\Domain\Teams\Team $homeTeam */
    $homeTeam = $player->team;
    /** @var \App\Domain\Teams\Team $awayTeam */
    $awayTeam = \App\Domain\Teams\Team::query()->whereHas('league', function(\Illuminate\Database\Eloquent\Builder $query) use ($homeTeam) {
        $query->where('id', '=', $homeTeam->league->id);
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
