<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\GamePlayer::class, function (Faker $faker) {

    //How to make sure player and game relation makes sense
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    /** @var \App\Domain\Models\Player $player */
    $player = factory(\App\Domain\Models\Player::class)->create();
    /** @var \App\Domain\Models\Team $homeTeam */
    $homeTeam = $player->team;
    /** @var \App\Domain\Models\Team $awayTeam */
    $awayTeam = \App\Domain\Models\Team::query()->whereHas('league', function(\Illuminate\Database\Eloquent\Builder $query) use ($homeTeam) {
        $query->where('id', '=', $homeTeam->league->id);
    })->where('id', '!=', $homeTeam->id)->first();

    $game = factory(\App\Domain\Models\Game::class)->create([
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id
    ]);

    return [
        'uuid' => $uuid,
        'player_id' => $player->id,
        'game_id' => $game->id,
        'initial_salary' => \App\Domain\Models\GamePlayer::MIN_SALARY,
        'salary' => \App\Domain\Models\GamePlayer::MIN_SALARY
    ];
});
