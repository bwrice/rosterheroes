<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Exceptions\InvalidWeekException;
use App\Jobs\CreatePlayerSpiritJob;

class BuildWeeklyPlayerSpiritsAction
{
    public function execute(Week $week)
    {
        $games = Game::query()->validForWeek($week);

        $games->each(function (Game $game) use ($week) {
            $game->homeTeam->players->each(function (Player $player) use ($week, $game) {
                CreatePlayerSpiritJob::dispatch($week, $game, $player);
            });
            $game->awayTeam->players->each(function (Player $player) use ($week, $game) {
                CreatePlayerSpiritJob::dispatch($week, $game, $player);
            });
        });
    }
}
