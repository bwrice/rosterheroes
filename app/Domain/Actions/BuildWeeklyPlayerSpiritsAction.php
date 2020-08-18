<?php


namespace App\Domain\Actions;


use App\Domain\Collections\GameCollection;
use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Jobs\CreateSpiritsForGameJob;

class BuildWeeklyPlayerSpiritsAction
{
    public function execute(Week $week)
    {
        Game::query()->validForWeek($week)->with([
            'homeTeam.players',
            'awayTeam.players'
        ])->chunk(10, function (GameCollection $games) use ($week) {
            $games->each(function (Game $game) use ($week) {
                CreateSpiritsForGameJob::dispatch($game, $week);
            });
        });
    }
}
