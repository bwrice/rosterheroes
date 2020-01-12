<?php

use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use Faker\Generator as Faker;
use Ramsey\Uuid\Uuid;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PlayerSpirit::class, function (Faker $faker) {

    $uuid = (string) Uuid::uuid4();

    return [
        'uuid' => $uuid,
        'week_id' => function () {
            return factory(Week::class)->create()->id;
        },
        'player_id' => function () {
            return factory(Player::class)->create()->id;
        },
        'game_id' => function () {
            return factory(Game::class)->create()->id;
        },
        'essence_cost' => 5000,
        'energy' => PlayerSpirit::STARTING_ENERGY
    ];
});

$factory->afterCreatingState(PlayerSpirit::class, 'with-stats', function (PlayerSpirit $playerSpirit, Faker $faker) {
    $gameLog = factory(\App\Domain\Models\PlayerGameLog::class)->state('with-stats')->create([
        'player_id' => $playerSpirit->player_id,
        'game_id' => $playerSpirit->game_id
    ]);
    $playerSpirit->player_game_log_id = $gameLog->id;
});
