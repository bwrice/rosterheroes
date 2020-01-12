<?php

use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\StatType;
use App\Domain\Models\Team;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PlayerGameLog::class, function (Faker $faker) {

    return [
        'game_id' => function() {
            return factory(Game::class)->create()->id;
        },
        'player_id' => function() {
            return factory(Player::class)->create()->id;
        },
        'team_id' => function() {
            return factory(Team::class)->create()->id;
        }
    ];
});

$factory->afterCreatingState(PlayerGameLog::class, 'with-stats', function (PlayerGameLog $gameLog, Faker $faker) {
    $player = $gameLog->player;
    $takeAmount = random_int(3, 7);
    $statTypes = StatType::query()->where('sport_id', '=', $player->team->league->sport_id)->inRandomOrder()->take($takeAmount)->get();
    $statTypes->each(function (StatType $statType) use ($gameLog) {
        $pointsPer = $statType->getBehavior()->getPointsPer();
        if ($pointsPer < 0) {
            $amount = 1;
        } else {
            $topRange = ceil(20/$pointsPer);
            $amount = rand(1, $topRange);
        }
        $gameLog->playerStats()->create([
            'amount' => $amount,
            'stat_type_id' => $statType->id
        ]);
    });
});
