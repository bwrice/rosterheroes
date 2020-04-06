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
        $games = Game::query()->validForWeek($week)->get();

        $games->each(function (Game $game) use ($week) {
            Player::query()->withNoSpiritForGame($game)->get()->each(function (Player $player) use ($game, $week) {
                CreatePlayerSpiritJob::dispatch($week, $game, $player);
            });
        });
    }
}
